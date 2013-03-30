<?php namespace Krucas\RBAuth\Contracts;

interface PermissionProviderInterface
{
    /**
     * Returns permission based on given identifier.
     *
     * @param $identifier
     * @return \Krucas\RBAuth\Contracts\PermissionInterface|null
     */
    public function getByIdentifier($identifier);
}