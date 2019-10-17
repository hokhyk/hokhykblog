<?php

namespace App\Providers;

//use App\Entities\User;
use Illuminate\Support\ServiceProvider;
//use App\Services\Voyager\IDivService;
//use App\Services\Voyager\BCDivService;
use App\Rules\NoneZeroDivisorValidator;
use \Illuminate\Support\Facades\Validator;

class VoyagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::resolver(
            function($translator, $data, $rules, $messages)
              {
                  return new NoneZeroDivisorValidator($translator, $data, $rules, $messages);
              }
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            'App\Services\Voyager\IAddService',
            'App\Services\Voyager\BCAddService'
        );

        $this->app->bind(
            'App\Services\Voyager\ISubService',
            'App\Services\Voyager\BCSubService'
        );

        $this->app->bind(
            'App\Services\Voyager\IMulService',
            'App\Services\Voyager\BCMulService'
        );

        $this->app->bind(
            'App\Services\Voyager\IDivService',
            'App\Services\Voyager\BCDivService'
        );
    }
}
