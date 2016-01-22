<?php

namespace SudoAgency\TatraBanka;

use Illuminate\Support\ServiceProvider;
use SudoAgency\TatraBanka\Tatrabanka;

class TatrabankaServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/tatrabanka.php' => config_path('tatrabanka.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tatrabanka', function($app)
        {
            return new Tatrabanka();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('tatrabanka');
    }
}
