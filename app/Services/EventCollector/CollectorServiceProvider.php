<?php

namespace App\Services\EventCollector;

use Illuminate\Support\ServiceProvider;
use App\Services\EventCollector\Contracts\Factory;
use App\Services\EventCollector\Geocoder;

class CollectorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGeocoder();
        $this->app->singleton(Factory::class, function ($app) {
            return new CollectorManager($app);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerGeocoder()
    {
        $this->app->singleton('geocoder', function ($app) {
            return new Geocoder(config('collectors.geocoder.token'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'geocoder',
            Factory::class
        ];
    }
}
