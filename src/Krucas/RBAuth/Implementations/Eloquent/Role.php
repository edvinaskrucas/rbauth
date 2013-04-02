<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Krucas\RBAuth\Contracts\RoleInterface;
use Krucas\RBAuth\Contracts\RoleProviderInterface;
use Illuminate\Database\Eloquent\Model;
use Krucas\RBAuth\Implementations\Eloquent\Exceptions\AccessNotFoundException;
use Krucas\RBAuth\Implementations\Eloquent\Exceptions\PermissionNotFoundException;
use Krucas\RBAuth\Implementations\Eloquent\Permission;

class Role extends Model implements RoleInterface, RoleProviderInterface
{
    /**
     * Table to store roles.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Returns all accessible permissions for a role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function access()
    {
        return $this->morphMany('Krucas\RBAuth\Implementations\Eloquent\Access', 'accessible');
    }

    /**
     * Determines if a role has access to given permission.
     *
     * @param $identifier
     * @return bool
     * @throws \Krucas\RBAuth\Implementations\Eloquent\Exceptions\AccessNotFoundException
     */
    public function can($identifier)
    {
        $permission = Permission::where('permission', $identifier)->first();

        if ($permission)
        {
            $access = $this->access()->where('permission_id', $permission->id)->first();

            if (!$access)
            {
                throw new AccessNotFoundException();
            }
            else
            {
                return $access->isEnabled();
            }
        }

        return false;
    }

    /**
     * Returns role name.
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->role;
    }

    /**
     * Returns role by its name.
     *
     * @param $roleName
     * @return \Krucas\RBAuth\Contracts\RoleInterface
     */
    public function getByName($roleName)
    {
        return static::where('role', '=', $roleName)->first();
    }

}