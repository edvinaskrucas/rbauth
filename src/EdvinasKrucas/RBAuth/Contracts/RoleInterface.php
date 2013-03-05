<?php namespace EdvinasKrucas\RBAuth\Contracts;

interface RoleInterface
{
    /**
     * Returns role name.
     *
     * @return string
     */
    public function getRoleName();
}