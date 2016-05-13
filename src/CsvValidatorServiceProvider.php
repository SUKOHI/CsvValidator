<?php namespace Sukohi\CsvValidator;

use Illuminate\Support\ServiceProvider;

class CsvValidatorServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var  bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['csv-validator'] = $this->app->share(function($app)
        {
            return new CsvValidator;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['csv-validator'];
    }

}