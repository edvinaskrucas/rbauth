<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\Guard;

class RBAuth extends Guard
{
    /**
     * Determines if a user has certain permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier)
    {
        if(!is_null($this->user()))
        {
            return $this->user()->can($identifier);
        }

        return false;
    }

    /**
     * Determines if a user has a given role.
     *
     * @param $roleName
     * @return bool
     */
    public function is($roleName)
    {
        if(!is_null($this->user()))
        {
            foreach($this->user()->getRoles() as $role)
            {
                if($role->getRoleName() == $roleName)
                {
                    return true;
                }
            }
        }

        return false;
    }
}