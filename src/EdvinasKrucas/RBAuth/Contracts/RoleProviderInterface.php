<?php namespace EdvinasKrucas\RBAuth\Contracts;

interface RoleProviderInterface
{
    /**
     * Returns role by its name.
     *
     * @param $roleName
     * @return \EdvinasKrucas\RBAuth\Contracts\RoleInterface
     */
    public function getByName($roleName);
}