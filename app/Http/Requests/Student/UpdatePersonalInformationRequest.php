<?php

namespace App\Http\Requests\Student;

use App\Models\student\StudentEmail;
use App\Models\student\StudentPhone;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * - Primary e-mail / phone are a one-time setup: once the e-mail is set
     *   they are locked and any submitted value is ignored server side.
     * - Hidden x-show inputs and blank contact values mean "not edited".
     */
    protected function prepareForValidation(): void
    {
        if ($this->user() && filled($this->user()->email)) {
            $this->request->remove('email');
            $this->request->remove('phone');
        } else {
            if (blank($this->input('email'))) {
                $this->request->remove('email');
            }
            if (blank($this->input('phone'))) {
                $this->request->remove('phone');
            }
        }

        if ($this->boolean('same_address')) {
            foreach (['permanent_address', 'permanent_city_id', 'permanent_country_id', 'permanent_post_code'] as $field) {
                $this->request->remove($field);
            }
        }

        // Address details are optional. An empty select/textarea means the
        // student did not change it, so it must not overwrite saved data.
        foreach ([
            'passport_number', 'passport_issue_date', 'passport_expiry_date',
            'current_address', 'current_city_id', 'current_country_id', 'current_post_code',
            'permanent_address', 'permanent_city_id', 'permanent_country_id', 'permanent_post_code',
        ] as $field) {
            if (blank($this->input($field))) {
                $this->request->remove($field);
            }
        }
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;
        $studentId = $this->user()?->student?->id;

        return [
            /*
            |--------------------------------------------------------------------------
            | Account (User) — one-time setup, globally unique
            |--------------------------------------------------------------------------
            */
            'name'  => ['sometimes', 'required', 'string', 'max:255'],

            'email' => [
                'sometimes',
                'nullable',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($userId),
                function ($attribute, $value, $fail) {
                    if (blank($value)) {
                        return;
                    }
                    if (StudentEmail::query()->where('email', $value)->exists()) {
                        $fail('This email address is already in use.');
                    }
                },
            ],

            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($userId),
                function ($attribute, $value, $fail) {
                    if (blank($value)) {
                        return;
                    }
                    if (StudentPhone::query()->where('phone', $value)->exists()) {
                        $fail('This phone number is already in use.');
                    }
                },
            ],

            /*
            |--------------------------------------------------------------------------
            | Student Information
            |--------------------------------------------------------------------------
            */
            'date_of_birth' => ['sometimes', 'required', 'date', 'before:today'],

            'gender' => ['sometimes', 'required', 'string', Rule::in(['Male', 'Female', 'Other'])],

            'marital_status' => ['sometimes', 'required', 'string', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */
            'passport_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                Rule::unique('students', 'passport_number')->ignore($studentId),
            ],

            'passport_issue_date' => ['sometimes', 'nullable', 'date'],

            'passport_expiry_date' => [
                'sometimes',
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    $issueDate = $this->input('passport_issue_date');

                    if (filled($issueDate) && filled($value)
                        && strtotime($value) < strtotime($issueDate)) {
                        $fail('The passport expiry date must be after the issue date.');
                    }
                },
            ],

            /*
            |--------------------------------------------------------------------------
            | Alternative contacts — globally unique across the whole system
            |--------------------------------------------------------------------------
            */
            'alt_phone'               => ['sometimes', 'nullable', 'array', 'max:2'],
            'alt_phone.*.value'       => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (blank($value)) {
                        return;
                    }
                    $slot = $this->slotFrom($attribute);

                    if ($this->alternativePhoneTaken($value, $slot)) {
                        $fail('This phone number is already in use.');

                        return;
                    }
                    if ($this->duplicatedIn($value, 'alt_phone', $slot, true)) {
                        $fail('This phone number is entered more than once.');
                    }
                },
            ],
            'alt_phone.*.is_whatsapp' => ['sometimes', 'boolean'],

            'alt_email'   => ['sometimes', 'nullable', 'array', 'max:2'],
            'alt_email.*' => [
                'sometimes',
                'nullable',
                'email',
                'max:191',
                function ($attribute, $value, $fail) {
                    if (blank($value)) {
                        return;
                    }
                    $slot = $this->slotFrom($attribute);

                    if ($this->alternativeEmailTaken($value, $slot)) {
                        $fail('This email address is already in use.');

                        return;
                    }
                    if ($this->duplicatedIn($value, 'alt_email', $slot, false)) {
                        $fail('This email address is entered more than once.');
                    }
                },
            ],

            /*
            |--------------------------------------------------------------------------
            | Study destination
            |--------------------------------------------------------------------------
            */
            'destination_id'   => ['required', 'array', 'min:1'],
            'destination_id.*' => ['required', 'string', 'max:100'],

            /*
            |--------------------------------------------------------------------------
            | Current address
            |--------------------------------------------------------------------------
            */
            'current_address'    => ['sometimes', 'nullable', 'string', 'max:1000'],
            'current_city_id'    => ['sometimes', 'nullable', 'uuid', 'exists:cities,id'],
            'current_country_id' => ['sometimes', 'nullable', 'uuid', 'exists:countries,id'],
            'current_post_code'  => ['sometimes', 'nullable', 'string', 'max:20'],

            /*
            |--------------------------------------------------------------------------
            | Same address / permanent address
            |--------------------------------------------------------------------------
            */
            'same_address' => ['sometimes', 'boolean'],

            'permanent_address'    => ['sometimes', 'nullable', 'string', 'max:1000'],
            'permanent_city_id'    => ['sometimes', 'nullable', 'uuid', 'exists:cities,id'],
            'permanent_country_id' => ['sometimes', 'nullable', 'uuid', 'exists:countries,id'],
            'permanent_post_code'  => ['sometimes', 'nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already in use.',
            'phone.unique' => 'This phone number is already in use.',
        ];
    }

    // ── Uniqueness helpers ──────────────────────────────────────────────────

    private function slotFrom(string $attribute): int
    {
        $parts = explode('.', $attribute);

        return (int) ($parts[1] ?? 0);
    }

    private function alternativePhoneTaken(string $value, int $slot): bool
    {
        $ignoreId = $this->user()?->student?->phones()
            ->where('sort_order', $slot)
            ->value('id');

        $query = StudentPhone::query()->where('phone', $value);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        // Matches any user (including the student's own primary phone).
        return $query->exists() || User::query()->where('phone', $value)->exists();
    }

    private function alternativeEmailTaken(string $value, int $slot): bool
    {
        $ignoreId = $this->user()?->student?->emails()
            ->where('sort_order', $slot)
            ->value('id');

        $query = StudentEmail::query()->where('email', $value);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists() || User::query()->where('email', $value)->exists();
    }

    private function duplicatedIn(string $value, string $field, int $slot, bool $nested): bool
    {
        foreach ($this->input($field, []) as $otherSlot => $entry) {
            if ((int) $otherSlot === $slot) {
                continue;
            }

            $other = $nested ? data_get($entry, 'value') : $entry;

            if (filled($other) && (string) $other === $value) {
                return true;
            }
        }

        return false;
    }
}
