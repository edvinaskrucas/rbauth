<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\EloquentUserProvider;

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

        $roleProvider = $this->createRbauthRoleProvider();

        return new RBAuth(
            $provider,
            $this->app['session'],
            $roleProvider,
            $this->app['config']
        );
    }

    /**
     * Create an instance of the RBAuth user provider.
     *
     * @return \Illuminate\Auth\Illuminate\Auth\EloquentUserProvider
     */
    protected function createRbauthProvider()
    {
        $model = $this->app['config']->get('rbauth::user_model');

        return new EloquentUserProvider($this->app['hash'], $model);
    }

    /**
     * Create an instance of Role provider.
     *
     * @return EdvinasKrucas\RBAuth\Contracts\RoleProviderInterface
     */
    protected function createRbauthRoleProvider()
    {
        $model = $this->app['config']->get('rbauth::role_provider');

        return new $model();
    }
}