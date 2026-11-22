<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SettingService
{
    /**
     * Cache key for all application settings.
     */
    protected string $cacheKey = 'app.settings.all';

    /**
     * Get a setting value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->all()->get($key);

        if (!$setting) {
            return $default;
        }

        return $this->castValue($setting->value, $setting->type);
    }

    /**
     * Check whether a setting exists.
     */
    public function has(string $key): bool
    {
        return $this->all()->has($key);
    }

    /**
     * Get all settings.
     */
    public function all(): Collection
    {
        return Cache::rememberForever(
            $this->cacheKey,
            fn () => Setting::query()->get()->keyBy('key')
        );
    }

    /**
     * Get settings by group.
     */
    public function group(string $group): Collection
    {
        return $this->all()
            ->filter(fn (Setting $setting) => $setting->group === $group)
            ->mapWithKeys(fn (Setting $setting) => [
                $setting->key => $this->castValue(
                    $setting->value,
                    $setting->type
                ),
            ]);
    }

    /**
     * Create or update a setting.
     */
    public function set(
        string $key,
        mixed $value,
        ?string $type = null,
        array $attributes = []
    ): Setting {
        $setting = DB::transaction(function () use (
            $key,
            $value,
            $type,
            $attributes
        ) {
            $existing = Setting::query()
                ->where('key', $key)
                ->first();

            $resolvedType = $type
                ?? $existing?->type
                ?? $this->detectType($value);

            $this->validateType($resolvedType);

            $data = [
                'value' => $this->prepareValue($value, $resolvedType),
                'type' => $resolvedType,
                ...$attributes,
            ];

            return Setting::query()->updateOrCreate(
                ['key' => $key],
                $data
            );
        });

        $this->clearCache();

        return $setting;
    }

    /**
     * Delete a setting.
     */
    public function forget(string $key): bool
    {
        $deleted = Setting::query()
            ->where('key', $key)
            ->delete();

        if ($deleted) {
            $this->clearCache();
        }

        return (bool) $deleted;
    }

    /**
     * Delete all settings belonging to a group.
     */
    public function forgetGroup(string $group): int
    {
        $deleted = Setting::query()
            ->where('group', $group)
            ->delete();

        if ($deleted > 0) {
            $this->clearCache();
        }

        return $deleted;
    }

    /**
     * Clear the settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Convert stored values into their appropriate PHP types.
     */
    protected function castValue(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer' => (int) $value,
            'float', 'double' => (float) $value,
            'boolean' => filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            ) ?? false,
            'json' => json_decode($value, true),
            'string' => (string) $value,
            default => $value,
        };
    }

    /**
     * Prepare values before storing them.
     */
    protected function prepareValue(mixed $value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'json' => json_encode($value, JSON_THROW_ON_ERROR),
            'boolean' => $value ? '1' : '0',
            'string' => (string) $value,
            'integer' => (string) (int) $value,
            'float', 'double' => (string) (float) $value,
            default => throw new InvalidArgumentException(
                "Unsupported setting type: {$type}"
            ),
        };
    }

    /**
     * Automatically detect the PHP value type.
     */
    protected function detectType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value), $value instanceof Collection => 'json',
            default => 'string',
        };
    }

    /**
     * Validate supported setting types.
     */
    protected function validateType(string $type): void
    {
        $allowedTypes = [
            'string',
            'integer',
            'float',
            'double',
            'boolean',
            'json',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            throw new InvalidArgumentException(
                "Unsupported setting type: {$type}"
            );
        }
    }
}
