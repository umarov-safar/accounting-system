<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\ServiceProvider;

class TestsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Executed when a test database is created or recreated
        ParallelTesting::setUpTestDatabase(function ($database, $token) {
            Artisan::call('db:seed --class=DatabaseSeederForTests');
        });
    }
}
