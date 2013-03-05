<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\AuthManager;

class RBAuthManager extends AuthManager
{
    /**
     * Create RBAuth driver instance.
     *
     * @return RBAuth
     */
    protected function createRbauthDriver()
    {
        $provider = $this->createRbauthProvider();

        return new RBAuth($provider, $this->app['session']);
    }

    /**
     * Create an instance of the RBAuth user provider.
     *
     * @return \Illuminate\Auth\Illuminate\Auth\EloquentUserProvider
     */
    protected function createRbauthProvider()
    {
        return $this->createEloquentProvider();
    }
}