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
        if (file_exists(config_path('http.php'))) {
            $this->mergeConfigFrom(config_path('http.php'), 'http-client');
        } else {
            $this->mergeConfigFrom(__DIR__.'/config/http.php', 'http-client');
        }
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
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
