<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Passport::tokensExpireIn(Carbon::now()->addDays(7));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(7));

        Passport::routes(function (RouteRegistrar $router) {
            //Only use password grant type routes
            config(['auth.guards.api.provider' => 'users']);
//            $router->forAuthorization();
            $router->forAccessTokens();
//            $router->forTransientTokens();
//            $router->forClients();
//            $router->forPersonalAccessTokens();

              // Customizing Models' used by Passport.
            Passport::useTokenModel(Token::class);
//            Passport::useClientModel(Client::class);
//            Passport::useAuthCodeModel(AuthCode::class);
//            Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        });

        /**
         * Register @see \App\Extensions\Illuminate\Auth\ExtendedUserProvider
         */
//        Auth::provider('extended', function ($app, $config) {
//            $model = $config['model'];
//            return new ExtendedUserProvider($app['hash'], $model);
//        });
    }
}
