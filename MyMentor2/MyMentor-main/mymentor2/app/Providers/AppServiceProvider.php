<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * In this project, we currently have no classes or interfaces that
     * require manual binding into the service container. This method
     * remains intentionally empty to satisfy the ServiceProvider contract.
     *
     * @return void
     */
    public function register(): void
    {
        // Intentionally left empty:
        // No manual service container bindings needed at this time.
        //
        // If future services require registration (e.g., interface bindings),
        // add them here, for example:
        // $this->app->singleton(SomeInterface::class, SomeImplementation::class);
    }

    /**
     * Bootstrap any application services.
     *
     * Currently, there is no additional bootstrapping logic required
     * (e.g., event listeners, route model binding, or publishing resources).
     * This method remains intentionally empty. Laravel will call it after
     * all other providers have been registered.
     *
     * @return void
     */
    public function boot(): void
    {
        // Intentionally left empty:
        // No additional bootstrapping (e.g., observers, macros, or
        // publishing) is needed for the application at this stage.
        //
        // If future bootstrapping tasks become necessary, add calls here,
        // such as:
        // Blade::component('package-component', PackageComponent::class);
    }
}
