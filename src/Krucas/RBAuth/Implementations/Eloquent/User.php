<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Krucas\RBAuth\Contracts\UserInterface;
use Krucas\RBAuth\Implementations\Eloquent\Exceptions\AccessNotFoundException;

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
        return $this->belongsToMany('Krucas\RBAuth\Implementations\Eloquent\Role', 'users_roles', 'user_id', 'role_id')
            ->orderBy('priority', 'ASC');
    }

    /**
     * Returns all accessible permissions for a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function access()
    {
        return $this->morphMany('Krucas\RBAuth\Implementations\Eloquent\Access', 'accessible');
    }

    /**
     * Determines if a user has access to given permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier)
    {
        $permission = Permission::where('permission', $identifier)->first();

        if ($permission)
        {
            $access = $this->access()->where('permission_id', $permission->id)->first();

            if ($access)
            {
                return $access->isEnabled();
            }
        }

        foreach ($this->getRoles() as $role)
        {
            try
            {
                return $role->can($identifier);
            }
            catch (AccessNotFoundException $ex)
            {
                // Handle this as you want.
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
     * Determines if a user is active or not.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active ? true : false;
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