<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VolumeAggregate
 *
 * @property int $dateline
 * @property float $volume
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VolumeAggregate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VolumeAggregate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VolumeAggregate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VolumeAggregate whereDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VolumeAggregate whereVolume($value)
 * @mixin \Eloquent
 * @property float $exchange_volume
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VolumeAggregate whereExchangeVolume($value)
 */
class VolumeAggregate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'volume_aggregate';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'dateline';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

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
        'dateline' => 0,
        'volume' => 0.00,
        'exchange_volume' => 0.00
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dateline', 'volume', 'exchange_volume'
    ];
}
