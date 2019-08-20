<?php

namespace App\Services;

use App\Entities\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserLoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

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

    public function authenticateUser( $credentials ) {
        try {
            $user = $this->userInstance->findForPassport($credentials[0]);

            return Hash::check($credentials[1], $user->password);
    }
    catch (Exception $e) {
            return false;
        }
    }

    public function getPassportAuthToken($crentials) {

        try {
            $authFullApiUrl = Config::get('app.token_url') . '/oauth/token';

            $headers = ['HTTP_ACCEPT' => 'application/json'];

            $data = array_merge(Config::get('passport'), [
                'username' => $crentials[0],
                'password' => $crentials[1],
                'scope'         => '*'
            ]);

            // Create and handle the oauth request
            $request = Request::create($authFullApiUrl, 'POST', $data, [], [], $headers);

            $response = App::handle($request);

            // response content as Array
            $content = \GuzzleHttp\json_decode($response->getContent(), true);

           return ['request' => $request, 'config'=> config('passport'), '$response' => $response];
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
