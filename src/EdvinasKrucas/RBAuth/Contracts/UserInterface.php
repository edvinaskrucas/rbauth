<?php namespace EdvinasKrucas\RBAuth\Contracts;

use Illuminate\Auth\UserInterface as AuthUserInterface;

interface UserInterface extends AuthUserInterface
{
    /**
     * Determines if a user has access to given permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier);

    /**
     * Determines if a user is assigned to a given role.
     *
     * @param $roleName
     * @return bool
     */
    public function is($roleName);

    /**
     * Returns user roles.
     *
     * @return array
     */
    public function getRoles();
}