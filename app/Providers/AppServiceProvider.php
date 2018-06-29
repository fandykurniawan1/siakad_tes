<?php

namespace App\Providers;

use Auth, Blade, Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // \DB::listen(function ($query) { \Log::info("[$query->time ms] " . $query->sql, $query->bindings); });
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        // Call to Laratrust::can
        Blade::directive('can', function ($expression) {
            return "<?php if (app('laratrust')->can({$expression})) : ?>";
        });
        Blade::directive('endcan', function () {
            return "<?php endif; // app('laratrust')->can ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
