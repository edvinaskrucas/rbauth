<?php namespace EdvinasKrucas\RBAuth\Contracts;

interface AccessInterface
{
    /**
     * Returns permission instance related to certain access instance.
     *
     * @return EdvinasKrucas\RBAuth\Contracts\PermissionInterface
     */
    public function permission();

    /**
     * Returns access status (true/false)
     *
     * @return bool
     */
    public function getStatus();
}