<?php

namespace App\Models\hrm;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'document_key',
        'document_name',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'status',
        'uploaded_by',
        'reviewed_by',
        'reviewed_at',
        'review_remarks',
        'issued_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'reviewed_at' => 'datetime',
            'issued_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    /**
     * Public URL for the stored file.
     *
     * All files handled by HandlesFiles live under public/uploads, so paths
     * are stored as "uploads/...". Older rows may still hold public-disk
     * style paths; those are normalised for the URL as a safety net.
     */
    public function getUrlAttribute(): ?string
    {
        $path = $this->file_path;

        if (! $path) {
            return null;
        }

        $path = str_starts_with($path, 'uploads/')
            ? $path
            : 'uploads/'.ltrim($path, '/');

        return asset($path);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }
}

