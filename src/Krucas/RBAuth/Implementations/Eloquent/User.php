<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Krucas\RBAuth\Contracts\UserInterface;

class User extends Model implements UserInterface
{
    /**
     * Table to store users.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Returns related roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Krucas\RBAuth\Implementations\Eloquent\Role', 'users_roles', 'user_id', 'role_id');
    }

    /**
     * Determines if a user has access to given permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier)
    {
        foreach ($this->getRoles() as $role)
        {
            if ($role->can($identifier))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Determines if a user is assigned to a given role.
     *
     * @param $roleName
     * @return bool
     */
    public function is($roleName)
    {
        foreach($this->getRoles() as $role)
        {
            if($role->getRoleName() == $roleName)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns user roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

}