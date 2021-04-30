<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\AssetExchangePriceHistory
 *
 * @property string $ticker_symbol
 * @property string $quote_symbol
 * @property string $exchange
 * @property int $dateline
 * @property float $price
 * @property float $volume
 * @property int $included
 * @property string|null $updated_at
 * @property-read \App\Asset $asset
 * @property-read \App\Exchange $exchangeRecord
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereExchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereQuoteSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetExchangePriceHistory whereVolume($value)
 * @mixin \Eloquent
 */
class AssetExchangePriceHistory extends Model
{
    use Traits\HasCompositePrimaryKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_exchange_price_history';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = ['ticker_symbol', 'quote_symbol', 'exchange', 'dateline'];

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
        'exchange' => '',
        'dateline' => '',
        'price' => 0.00000000,
        'volume' => 0.00000000,
        'included' => true,
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
     * Get the exchange that owns the exchange history entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exchangeRecord()
    {
        return $this->belongsTo('App\Exchange', 'exchange', 'exchange');
    }

    /**
     * Get the asset that owns the exchange entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo('App\Asset', 'ticker_symbol', 'ticker_symbol');
    }
}
