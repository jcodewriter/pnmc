<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



/**
 * App\Asset
 *
 * @property string $ticker_symbol
 * @property string $title
 * @property string|null $icon_file
 * @property float $price
 * @property float $exchange_price
 * @property float $price_change
 * @property float $exchange_price_change
 * @property float $volume
 * @property float $exchange_volume
 * @property float $volume_price
 * @property float $volume_in
 * @property float $volume_in_price
 * @property float $volume_tx
 * @property float $volume_tx_price
 * @property float $volume_out
 * @property float $volume_out_price
 * @property float $supply
 * @property float $supply_change
 * @property int $height
 * @property int $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $exchange_price_updated_at
 * @property int $exchange_price_dateline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ExchangeHistory[] $exchangeHistory
 * @property-read int|null $exchange_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ExchangePriceHistory[] $exchangePriceHistory
 * @property-read int|null $exchange_price_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Exchange[] $exchanges
 * @property-read int|null $exchanges_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AssetHistory[] $history
 * @property-read int|null $history_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Asset onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereExchangePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereExchangePriceChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereExchangePriceDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereExchangePriceUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereExchangeVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereIconFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset wherePriceChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereSupplyChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumeInPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumeOutPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumeTx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Asset whereVolumeTxPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Asset withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Asset withoutTrashed()
 * @mixin \Eloquent
 */
class Asset extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ticker_symbol';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
        'price' => 0.00000000,
        'exchange_price' => 0.00000000,
        'exchange_price_change' => 0.00,
        'price_change' => 0.00,
        'volume' => 0.00000000,
        'exchange_volume' => 0.00000000,
        'volume_in' => 0.00000000,
        'volume_out' => 0.00000000,
        'volume_tx' => 0.00000000,
        'supply' => 0.00000000,
        'supply_change' => 0.00,
        'height' => 0,
        'updated_at' => 0,
        'exchange_price_dateline' => 0
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


    public static function filterForValidColumns(array $columns)
    {
        return array_intersect($columns, [
            'ticker_symbol',
            'title',
            'icon_file',
            'price',
            'exchange_price',
            'exchange_price_change',
            'price_change',
            'volume',
            'exchange_volume',
            'volume_in',
            'volume_in_price',
            'volume_out',
            'volume_out_price',
            'volume_tx',
            'volume_tx_price',
            'supply',
            'supply_change',
            'height',
            'updated_at',
            'deleted_at',
            'exchange_price_updated_at',
            'exchange_price_dateline',
        ]);
    }


    /**
     * Get the history entries for the asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('App\AssetHistory', 'ticker_symbol');
    }

    /**
     * Get the exchange entries for the asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchanges()
    {
        return $this->hasMany('App\Exchange', 'ticker_symbol', 'ticker_symbol');
    }

    /**
     * Get the exchange history entries for the asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangeHistory()
    {
        return $this->hasMany('App\ExchangeHistory', 'ticker_symbol', 'ticker_symbol');
    }

    /**
     * Get the exchange price history entries for the asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangePriceHistory()
    {
        return $this->hasMany('App\ExchangePriceHistory', 'ticker_symbol', 'ticker_symbol');
    }

    /**
     *
     */
    public function calculateChanges()
    {
        /** @var AssetHistory|null $result */
        $result = $this->history()
            ->where('dateline', '<=', $this->updated_at - 86400)
            ->orderBy('dateline', 'DESC')
            ->first()
        ;
        if ($result)
        {
            if ($this->supply == 0.00 && $result->supply == 0.00)
            {
                $this->supply_change = 0.0;
            }
            else if ($result->supply > 0.0)
            {
                $this->supply_change = sprintf("%.2f", (($this->supply - $result->supply) / $result->supply) * 100);
            }
            else
            {
                $this->supply_change = 100.0;
            }

            if ($this->price == 0.00 && $result->price == 0.00)
            {
                $this->price_change = 0.0;
            }
            else if ($result->price > 0.0)
            {
                $this->price_change = sprintf("%.2f", (($this->price - $result->price) / $result->price) * 100);
            }
            else
            {
                $this->price_change = 100.0;
            }
        }
        else
        {
            $this->price_change = 0.0;
        }


        /** @var Collection|AssetHistory[]|null $results */
        $results = $this->history()
            ->where('dateline', '>=', $this->updated_at - 86400)
            ->get()
        ;
        if ($results)
        {
            $this->volume = $results->sum('volume');
            $this->volume_price = $results->sum(function($model)
            {
                return $model['price'] * $model['volume'];
            });

            $this->volume_in = $results->sum('volume_in');
            $this->volume_in_price = $results->sum(function($model)
            {
                return $model['price'] * $model['volume_in'];
            });

            $this->volume_out = $results->sum('volume_out');
            $this->volume_out_price = $results->sum(function($model)
            {
                return $model['price'] * $model['volume_out'];
            });

            $this->volume_tx = $results->sum('volume_tx');
            $this->volume_tx_price = $results->sum(function($model)
            {
                return $model['price'] * $model['volume_tx'];
            });
        }
    }

    /**
     *
     */
    public function calculateExchangePriceChange()
    {
        /** @var ExchangePriceHistory $dateline */
        $dateline = $this->exchangePriceHistory()
            ->where('dateline', '<=', $this->exchange_price_dateline - 86400)
            ->where('price', '!=', 0.0)
            ->orderBy('dateline', 'desc')
            ->limit(1)
            ->first()
        ;

        if ($dateline) {
            /** @var ExchangePriceHistory|null $result */
            $result = $this->exchangePriceHistory()
                ->selectRaw('avg(price) AS exchange_price_yesterday')
                ->where('dateline', '=', $dateline->dateline)
                ->where('price', '!=', 0.0)
                ->first();
            if ($result) {
                $exchangePrice = $result->getAttribute('exchange_price_yesterday');

                if ($this->exchange_price == 0.00 && $exchangePrice == 0.00) {
                    $this->exchange_price_change = 0.0;
                } else if ($exchangePrice > 0.0) {
                    $this->exchange_price_change = sprintf("%.2f", (($this->exchange_price - $exchangePrice) / $exchangePrice) * 100);
                } else {
                    $this->exchange_price_change = 0.0;
                }
            } else {
                $this->exchange_price_change = 0.0;
            }
        } else {
            $this->exchange_price_change = 0.0;
        }
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return Collection
     */
    public function newCollection(array $models = [])
    {
//        return (new Collection($models));
        return (new Collection($models))->sortBy(function($model){
            return $model['price'] * $model['supply'];
        }, SORT_NUMERIC, true);
    }

    /**
     * @return array
     */
    public function getVolumeGraphData()
    {
        return $this->history->pluck('volume')
            ->all()
            ;
    }

    /**
     * @return array
     */
    public function getSupplyGraphData()
    {
        return $this->history->pluck('supply')
            ->all()
            ;
    }

    /**
     * @return array
     */
    public function getPriceGraphData()
    {
        return $this->history->pluck('price')
            ->all()
            ;
    }
}
