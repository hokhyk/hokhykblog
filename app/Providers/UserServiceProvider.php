<?php

namespace App\Providers;

use App\Entities\User;
use Illuminate\Support\ServiceProvider;

use App\Services\UserLoginService;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('App\Services\UserLoginService');

    }
}
