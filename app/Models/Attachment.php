<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'filename',
        'extension',
        'letter_id',
        'user_id',
    ];

    protected $appends = [
        'path_url',
    ];

    /**
     * @return string
     */
    public function getPathUrlAttribute(): string {
        if (!is_null($this->path)) {
            return $this->path;
        }

        return asset('storage/attachments/' . $this->filename);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function($query, $find) {
            return $query
                ->where('filename', 'LIKE', '%' . $find . '%')
                ->orWhereHas('letter', function ($query) use ($find) {
                    return $query->where('letter_number', $find);
                });
        });
    }

    public function scopeRender($query, $search)
    {
        return $query
            ->with(['letter'])
            ->search($search)
            ->latest('created_at')
            ->appends([
                'search' => $search,
            ]);
    }

    /**
     * @return BelongsTo
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
