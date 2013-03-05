<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\AuthServiceProvider;
use EdvinasKrucas\RBAuth\RBAuthManager;

class RBAuthServiceProvider extends AuthServiceProvider
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
        $this->app['auth'] = $this->app->share(function($app)
        {
            $app['auth.loaded'] = true;

            return new RBAuthManager($app);
        });
    }
}