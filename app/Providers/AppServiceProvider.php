<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use mysql_xdevapi\Schema;
use Illuminate\Pagination\Paginator;



class AppServiceProvider extends ServiceProvider
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
       // Schema::defaultStringLenght(191);
        { Paginator::useBootstrap(); }
    }



}
