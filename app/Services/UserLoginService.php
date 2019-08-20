<?php

namespace App\Services;

use App\Entities\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserLoginRequest;
use Illuminate\Support\Facades\Hash;


class UserLoginService
{
    /**
     * @var User
     */
    protected $userInstance;

    public function __construct(User $user)
    {
        $this->userInstance = $user;
    }



    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password, $hashed_password)
    {
        return Hash::check($password, $this->password);
    }

    public function authenticateUser( string $login, string $password )  {
        try {
            $user = $this->userInstance->findForPassport($login);

            return Hash::check($password, $user->password);;
    }
    catch (Exception $e) {
            return false;
        }
    }



    public function login(UserLoginRequest $request) {

        $login = $request->get('name') ?: $request->get('phone') ?: $request->get('email') ?: null;

        if( $login ) {

            return $this->authenticateUser($login, $request->get('password'));
        }
        return false;
    }

    public function getPassportAuthToken(UserLoginRequest $request) {

        try {
            $request->request;

            $agent = Request::create('POST', request()->root() . '/oauth/token', [
                'form_params' => config('passport') + $request->only(array_keys($request->rules()))
            ]);

           return Route::dispatch($agent);
        }
        catch (Exception $e) {

            return [
                'code' => $e->getCode(),
                'error' => 'true',
                'message' => $e->getMessage()
            ];
        }
    }
}
