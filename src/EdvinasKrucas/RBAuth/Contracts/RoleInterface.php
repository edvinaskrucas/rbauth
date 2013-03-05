<?php namespace EdvinasKrucas\RBAuth\Contracts;

interface RoleInterface
{
    /**
     * Determine if a role has access to a given permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier);

    /**
     * Returns role name.
     *
     * @return string
     */
    public function getRoleName();
}