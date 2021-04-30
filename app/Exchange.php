<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Exchange
 *
 * @property string $ticker_symbol
 * @property string $quote_symbol
 * @property string $exchange
 * @property float $price
 * @property float $price_change
 * @property float $volume
 * @property-read \App\Asset $asset
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereExchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange wherePriceChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereQuoteSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereVolume($value)
 * @mixin \Eloquent
 * @property float $spread
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereSpread($value)
 */
class Exchange extends Model
{
    use Traits\HasCompositePrimaryKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchange';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = ['ticker_symbol', 'quote_symbol', 'exchange'];

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
        'price' => 0.00000000,
        'price_change' => 0.00,
        'volume' => 0.00000000,
        'spread' => 0.00000000,
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
     * Get the asset that owns the exchange entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo('App\Asset', 'ticker_symbol', 'ticker_symbol');
    }
}
