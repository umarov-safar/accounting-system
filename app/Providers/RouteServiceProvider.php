<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            /**
             * @psalm-suppress PossiblyNullArgument
             */
            Route::namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
        });
    }
}
