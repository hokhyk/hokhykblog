<?php

namespace App\Services;

use App\Entities\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserLoginRequest;


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

    public function authenticateUser(string $login) : bool {
        try {
            $user = $this->userInstance->findForPassport($login);

            return $this->userInstance->validateForPassportPasswordGrant($user->password);
    }
    catch (Exception $e) {
            return false;
        }
    }



    public function login(Request $request) {

        $login = $request->get('name') ?: $request->get('phone') ?: $request->get('email') ?: null;

        if( $login ) {

            return $this->authenticateUser($login);
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
