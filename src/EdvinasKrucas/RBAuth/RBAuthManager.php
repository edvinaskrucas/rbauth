<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\AuthManager;

class RBAuthManager extends AuthManager
{
    protected function createRbauthDriver()
    {
        $provider = $this->createEloquentProvider();

        return new RBAuth($provider, $this->app['session']);
    }
}