<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\AssetHistory
 *
 * @property string $ticker_symbol
 * @property int $height
 * @property float $price
 * @property float $volume
 * @property float $volume_in
 * @property float $volume_tx
 * @property float $volume_out
 * @property float $supply
 * @property int $dateline
 * @property string|null $updated_at
 * @property-read \App\Asset $asset
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereVolumeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereVolumeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AssetHistory whereVolumeTx($value)
 * @mixin \Eloquent
 */
class AssetHistory extends Model
{
    use Traits\HasCompositePrimaryKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_history';

    /**
     * The primary key for the model.
     *
     * @var array
     */
    protected $primaryKey = ['ticker_symbol', 'height'];

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
        'height' => 0,
        'price' => 0.00000000,
        'volume' => 0.00000000,
        'volume_in' => 0.00000000,
        'volume_out' => 0.00000000,
        'volume_tx' => 0.00000000,
        'supply' => 0.00000000,
        'dateline' => 0,
        'updated_at' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticker_symbol', 'height', 'price', 'dateline', 'updated_at',
        'volume', 'volume_in', 'volume_out', 'volume_tx', 'supply',
    ];

    /**
     * Get the asset that owns the history entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo('App\Asset', 'ticker_symbol');
    }
}
