<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CountriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('countries', function () {
            return json_decode(file_get_contents(resource_path('assets/countries.json')));
          });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $countriesList = file_get_contents(base_path('resources/assets/countries.json'));
        $countriesListData = json_decode($countriesList);

        // Share all menuData to all the views
        \View::share('countriesListData', [$countriesListData]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['countries'];
    }
}
