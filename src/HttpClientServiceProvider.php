<?php

/**
 * @package TakerIo\HttpClient
 * @author Alexander Burakovskiy <aburakovskiy@taker.io>
 */

namespace TakerIo\HttpClient;

use TakerIo\HttpClient\Commands\Clean;
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
        $this->publishes([__DIR__ . '/config/http.php' => base_path('config/http.php')]);
        $this->commands(Clean::class);
        $this->publishes([
            __DIR__.'/database/migrations/create_http_logs_table.php' => database_path('migrations/'.date('Y_m_d_His').'_create_http_logs_table.php'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (file_exists(base_path('config/http.php'))) {
            $this->mergeConfigFrom(base_path('config/http.php'), 'http-client');
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
