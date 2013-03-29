<?php namespace Krucas\RBAuth\Contracts;

interface RoleProviderInterface
{
    /**
     * Returns role by its name.
     *
     * @param $roleName
     * @return \Krucas\RBAuth\Contracts\RoleInterface
     */
    public function getByName($roleName);
}