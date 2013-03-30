<?php namespace Krucas\RBAuth\Contracts;

interface PermissionInterface
{
    /**
     * Returns value on which be checking for permission.
     *
     * @return mixed
     */
    public function getIdentifier();
}