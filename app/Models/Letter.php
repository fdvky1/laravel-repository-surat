<?php

namespace App\Models;

use App\Enums\Config as ConfigEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Letter extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'letter_number',
        'from',
        'to',
        'received_date',
        'summary',
        'note',
        'classification_code',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'letter_date' => 'date',
        'received_date' => 'date',
    ];

    protected $appends = [
        'formatted_letter_date',
        'formatted_received_date',
        'formatted_created_at',
        'formatted_updated_at',
    ];

    public function getFormattedLetterDateAttribute(): string {
        return Carbon::parse($this->created_at)->isoFormat('dddd, D MMMM YYYY');
    }

    public function getFormattedReceivedDateAttribute(): string {
        return Carbon::parse($this->received_date)->isoFormat('dddd, D MMMM YYYY');
    }

    public function getFormattedCreatedAtAttribute(): string {
        return Carbon::parse($this->created_at)->isoFormat('dddd, D MMMM YYYY, HH:mm:ss');
    }

    public function getFormattedUpdatedAtAttribute(): string {
        return Carbon::parse($this->updated_at)->isoFormat('dddd, D MMMM YYYY, HH:mm:ss');
    }

    public function scopeType($query, $type, $uid)
    {
        return $query->where(function($query) use ($type, $uid){
            return $type == 'incoming' ? $query->where('to', $uid) : $query->where('from', $uid);
        });
    }

    public function scopeIncoming($query, $uid)
    {
        return $this->scopeType($query, 'incoming', $uid);
    }

    public function scopeOutgoing($query, $uid)
    {
        return $this->scopeType($query, 'outgoing', $uid);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now());
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('created_at', now()->addDays(-1));
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function($query, $find) {
            return $query
                ->where('letter_number', $find)
                ->orWhere('from', 'LIKE', $find . '%')
                ->orWhere('to', 'LIKE', $find . '%');
        });
    }

    public function scopeRender($query, $search)
    {
        return $query
            ->with(['attachments', 'classification'])
            ->search($search)
            ->latest('created_at');
    }

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from');
    }

    /**
     * @return BelongsTo
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to');
    }

    /**
     * @return BelongsTo
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class, 'classification_code', 'code');
    }

    /**
     * @return HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'letter_id', 'id');
    }
}
