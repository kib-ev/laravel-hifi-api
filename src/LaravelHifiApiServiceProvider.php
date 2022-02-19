<?php

namespace KibEv\LaravelHifiApi;

use Illuminate\Support\ServiceProvider;

class LaravelHifiApiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'kib-ev');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'kib-ev');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-hifi-api.php', 'laravel-hifi-api');

        // Register the service the package provides.
        $this->app->singleton('laravel-hifi-api', function ($app) {
            return new LaravelHifiApi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-hifi-api'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-hifi-api.php' => config_path('laravel-hifi-api.php'),
        ], 'laravel-hifi-api.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/kib-ev'),
        ], 'laravel-hifi-api.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/kib-ev'),
        ], 'laravel-hifi-api.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/kib-ev'),
        ], 'laravel-hifi-api.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
