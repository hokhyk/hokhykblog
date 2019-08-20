<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\UserLoginRequest;
use App\Http\Controllers\Controller as BaseController;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Eloquent\User\UserRepositoryEloquent as UserRepository;
use App\Validators\User\UserValidator;
use Exception;
use App\Services\UserLoginService;
use Illuminate\Support\Facades\Auth;


class UserAuthenticationController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $user_repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    protected $userLoginService;

    public function __construct(UserRepository $user_repository, UserValidator $validator, UserLoginService $userLoginService)
    {
        $this->user_repository = $user_repository;

        $this->validator = $validator;

        $this->userLoginService = $userLoginService;

    }


    public function Login(UserLoginRequest $request) {
        try{
            $commonAuth = $this->userLoginService->login($request);

             return response()->json(['code' => '1', 'commonAuth' => $commonAuth, 'token' => $this->userLoginService->getPassportAuthToken($request)]);
        }
        catch (Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'error' => 'true',
                'message' => $e->getMessage()
            ]);
        }
    }

    public  function Logout() {
//
//        if (Auth::guard('api')->check()) {
//            Auth::guard('api')->user()->token()->delete();
//        }
//
//        return response()->json(['message' => '登出成功', 'status_code' => 200, 'data' => null]);
    }
}
