<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $client_id
 * @property string $quote_number
 * @property string $status
 * @property string $issue_date
 * @property string|null $due_date
 * @property numeric $discount
 * @property numeric $total_amount
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuoteItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereQuoteNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereUserId($value)
 * @mixin \Eloquent
 */
class Quote extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'quote_number',
        'status',
        'issue_date',
        'due_date',
        'discount',
        'total_amount',
        'notes',
        'share_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quote) {
            $quote->share_token = \Illuminate\Support\Str::random(32);
        });
    }

    /**
     * Get the public sharing URL.
     */
    public function getShareUrlAttribute(): string
    {
        return route('public.quote.show', $this->share_token);
    }

    /**
     * Get the user that owns this quote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client for this quote.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the items for this quote.
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}
