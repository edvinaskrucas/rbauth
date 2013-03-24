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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $this->app['auth']->extend('rbauth', function() use ($app)
        {
            return new RBAuth(
                new EloquentUserProvider(
                    $app['hash'],
                    $app['config']->get('rbauth:user_model')
                ),
                $app['session'],
                new $app['config']->get('rbauth::role_provider'),
                $app['config']
            );
        });

        $this->app['config']->package('edvinaskrucas/rbauth', __DIR__.'/../config');
    }
}