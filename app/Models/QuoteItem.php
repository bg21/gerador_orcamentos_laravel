<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $quote_id
 * @property string $description
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Quote $quote
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereQuoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    /**
     * Get the quote that owns this item.
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
