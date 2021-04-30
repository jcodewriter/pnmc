<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Admin
 *
 * @property string $ticker_symbol
 * @property int $dateline
 * @property float $price
 * @property float $volume
 * @property float $supply
 * @property int $height
 * @property-read \App\Asset $asset
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereDateline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereVolume($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereHeight($value)
 * @property int $user_id
 * @property int $is_super_admin
 * @property int $can_manage_translations
 * @property int $can_manage_users
 * @property int $can_manage_assets
 * @property int $can_manage_settings
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereCanManageAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereCanManageSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereCanManageTranslations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereCanManageUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereIsSuperAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin whereUserId($value)
 */
class Admin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

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
        'user_id' => 0,
        'is_super_admin' => false,
        'can_manage_translations' => false,
        'can_manage_users' => false,
        'can_manage_assets' => false,
        'can_manage_settings' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'is_super_admin', 'can_manage_translations',
        'can_manage_users', 'can_manage_assets', 'can_manage_settings',
    ];

    /**
     * Get the user that owns the admin entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission)
    {
        if ($this->is_super_admin)
        {
            return true;
        }

        $permissionFlag = "can_manage_$permission";
        return $this->$permissionFlag ?? false;
    }
}
