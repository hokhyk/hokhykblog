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

use GuzzleHttp\Client;

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


    public function authenticateUser( $credentials ) {
        try {
            $user = $this->userInstance->findForPassport($credentials['login']);

            return Hash::check($credentials['password'], $user->password);
    }
    catch (Exception $e) {
            return false;
        }
    }

    public function getPassportAuthToken($crentials) {

        try {
            $authFullApiUrl = Config::get('app.token_url') . '/oauth/token';

//            $headers = ['HTTP_ACCEPT' => 'application/json'];

            $data = ['form_params' => array_merge(Config::get('passport'), [
                'username' => $crentials['login'],
//                'username' => 'hokhyk@aliyun.com',
                'password' => $crentials['password'],
//                'password' => '9900ii',
                'scope'         => '*'
            ])];

            // Create and handle the oauth request
//            $request = Request::create($authFullApiUrl, 'POST', $data, [], [], $headers);
//
//            $response = App::handle($request);

            $guzzle = new Client();

//            $response = $guzzle->request('POST',$authFullApiUrl, $data, $headers);
            $response = $guzzle->post($authFullApiUrl, $data);

//            return ['request' => $data, '$authFullApiUrl'=> $authFullApiUrl, '$response' => $response];

            return response()->json($response->getBody()->getContents());
        }
        catch (Exception $e) {

//            return [
//                'code' => $e->getCode(),
//                'error' => 'true',
//                'message' => $e->getMessage()
//            ];
            throw new Exception($e->getMessage());
        }
    }
}
