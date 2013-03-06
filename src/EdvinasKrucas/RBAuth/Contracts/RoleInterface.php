<?php namespace EdvinasKrucas\RBAuth\Contracts;

interface RoleInterface
{
    /**
     * Determines if a role has access to given permission.
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