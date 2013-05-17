<?php namespace Krucas\RBAuth;

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

        $this->registerFilters();
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
     * @return \Krucas\RBAuth\Contracts\RoleProviderInterface
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

    /**
     * Registers auth filters to use in routes.
     *
     * @return void
     */
    protected function registerFilters()
    {
        list($app) = array($this->app);

        $this->app['router']->filter('can', function($route, $request, $value, $arg0 = null, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null) use ($app)
        {
            if (!$app['auth']->fromRoute($route)->can($value, $arg0, $arg1, $arg2, $arg3, $arg4))
            {
                return $app['redirect']->to('/');
            }
        });

        $this->app['router']->filter('authIgnoreSuper', function($route, $request) use ($app)
        {
            $app['auth']->ignoreSuper();
        });

        $this->app['router']->filter('authIgnoreCallback', function($route, $request) use ($app)
        {
            $app['auth']->ignoreCallback();
        });
    }
}