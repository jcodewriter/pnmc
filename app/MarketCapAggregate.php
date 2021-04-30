<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MarketCapAggregate
 *
 * @property int $dateline
 * @property float $market_cap
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MarketCapAggregate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MarketCapAggregate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MarketCapAggregate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MarketCapAggregate whereDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MarketCapAggregate whereMarketCap($value)
 * @mixin \Eloquent
 */
class MarketCapAggregate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'market_cap_aggregate';
    
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
        'market_cap' => 0.00
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dateline', 'market_cap'
    ];
}
