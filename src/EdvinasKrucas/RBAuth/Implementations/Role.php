<?php namespace EdvinasKrucas\RBAuth\Implementations;

use EdvinasKrucas\RBAuth\Contracts\RoleInterface;
use EdvinasKrucas\RBAuth\Contracts\RoleProviderInterface;
use Illuminate\Database\Eloquent\Model;

class Role extends Model implements RoleInterface, RoleProviderInterface
{
    /**
     * Table to store roles.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Determines if a role has access to given permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier)
    {
        // TODO: Implement can() method.
        return true;
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
     * @return \EdvinasKrucas\RBAuth\Contracts\RoleInterface
     */
    public function getByName($roleName)
    {
        return static::where('role', '=', $roleName)->first();
    }

}