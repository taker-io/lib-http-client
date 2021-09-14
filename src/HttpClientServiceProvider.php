<?php

/**
 * @package TakerIo\HttpClient
 * @author Alexander Burakovskiy <aburakovskiy@taker.io>
 */

namespace TakerIo\HttpClient;

use Illuminate\Support\ServiceProvider;

class HttpClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/http.php' => config_path('http.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->app->bind('http-client', function () {
            return new Antpool(config('antpool.username'), config('antpool.api_key'), config('antpool.api_secret'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [HTTP::class];
    }
}
