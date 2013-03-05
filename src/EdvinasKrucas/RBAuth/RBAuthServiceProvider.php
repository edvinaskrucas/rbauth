<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Support\ServiceProvider;
use EdvinasKrucas\RBAuth\RBAuth;

class RBAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('edvinaskrucas/rbauth');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['rbauth'] = $this->app->share(function($app)
        {
            return new RBAuth();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}