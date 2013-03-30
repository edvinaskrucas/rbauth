<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Krucas\RBAuth\Contracts\PermissionInterface;

class Permission extends Model implements PermissionInterface
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

}