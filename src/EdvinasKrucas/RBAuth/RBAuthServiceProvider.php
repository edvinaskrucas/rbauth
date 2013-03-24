<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\ServiceProvider;

class RBAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->package('edvinaskrucas/rbauth');

        $this->extendAuth();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['config']->package('edvinaskrucas/rbauth', __DIR__.'/../config');
    }

    /**
     * Creates new instance of defined role provider.
     *
     * @return \EdvinasKrucas\RBAuth\Contracts\RoleProviderInterface
     */
    protected function createRoleProvider()
    {
        $class = $this->app['config']->get('rbauth::role_provider');

        return new $class;
    }

    /**
     * Extends auth driver with RBAuth
     *
     * @return void
     */
    protected function extendAuth()
    {
        $app = $this->app;

        $roleProvider = $this->createRoleProvider();

        $this->app['auth']->extend('rbauth', function() use ($app, $roleProvider)
        {
            return new RBAuth(
                new EloquentUserProvider(
                    $app['hash'],
                    $app['config']->get('rbauth::user_model')
                ),
                $app['session'],
                $roleProvider,
                $app['config']
            );
        });
    }
}