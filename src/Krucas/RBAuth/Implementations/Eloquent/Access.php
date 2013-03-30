<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    /**
     * Access is enabled.
     */
    const ACCESS_ENABLED        = 1;

    /**
     * Access is disabled.
     */
    const ACCESS_DISABLED       = 0;

    /**
     * Table to store access items.
     *
     * @var string
     */
    protected $table = 'access';

    /**
     * Returns accessible object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accessible()
    {
        return $this->morphTo();
    }

    /**
     * Returns related permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo('Krucas\RBAuth\Implementations\Eloquent\Permission', 'permission_id');
    }

    /**
     * Determines if a access is enabled or disabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->status == static::ACCESS_ENABLED ? true : false;
    }
}