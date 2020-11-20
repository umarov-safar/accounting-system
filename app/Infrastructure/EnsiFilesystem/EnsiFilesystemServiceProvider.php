<?php

namespace App\Infrastructure\EnsiFilesystem;

use Illuminate\Support\ServiceProvider;

class EnsiFilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ensi.filesystem', function ($app) {
            return new EnsiFilesystemManager($app);
        });
    }
}
