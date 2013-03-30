<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Krucas\RBAuth\Contracts\PermissionInterface;
use Krucas\RBAuth\Contracts\PermissionProviderInterface;

class Permission extends Model implements PermissionInterface, PermissionProviderInterface
{
    /**
     * Table to store permissions.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Returns value on which be checking for permission.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->permission;
    }

    /**
     * Returns permission based on given identifier.
     *
     * @param $identifier
     * @return \Krucas\RBAuth\Contracts\PermissionInterface|null
     */
    public function getByIdentifier($identifier)
    {
        return static::where('permission', $identifier)->first();
    }

}