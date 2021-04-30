<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\ExchangePriceHistory
 *
 * @property string $ticker_symbol
 * @property string $quote_symbol
 * @property int $dateline
 * @property float $price
 * @property string|null $updated_at
 * @property-read \App\Asset $asset
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory whereDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory whereQuoteSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangePriceHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExchangePriceHistory extends Model
{
    use Traits\HasCompositePrimaryKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchange_price_history';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = ['ticker_symbol', 'quote_symbol', 'dateline'];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'array';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ticker_symbol' => '',
        'quote_symbol' => '',
        'dateline' => '',
        'price' => 0.00000000,
        'updated_at' => ''
    ];

    /**
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the asset that owns the exchange price history entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo('App\Asset', 'ticker_symbol', 'ticker_symbol');
    }
}
