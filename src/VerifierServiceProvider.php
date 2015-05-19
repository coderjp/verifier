<?php namespace Coderjp\Verifier;

/**
 * This file is part of Verifier,
 *
 * @license MIT
 * @package Coderjp\Verifier
 */

use Illuminate\Support\ServiceProvider;

class VerifierServiceProvider extends ServiceProvider {

    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('verifier.php'),
        ]);

        $this->commands('command.verifier.migration');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('command.verifier.migration', function ($app) {
            return new MigrationCommand();
        });
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'command.verifier.migration'
        );
    }

}