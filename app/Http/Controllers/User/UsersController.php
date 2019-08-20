<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\UserInfoResource;
use App\Http\Resources\UsersCollection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller as BaseController;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Repositories\Eloquent\User\UserRepositoryEloquent as UserRepository;
use App\Validators\User\UserValidator;
use Illuminate\Http\Response;
use Exception;

/**
 * Class UsersController.
 *
 * @package namespace App\Http\Controllers\User;
 */
class UsersController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $repository
     * @param UserValidator $validator
     */
    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $user = $this->repository->find($id);

            $response = [
                'code'    => Response::HTTP_OK,
                'message' => 'User found.',
                'result'  => new UserInfoResource($user),
            ];

            if (request()->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {

            if (request()->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $User = $this->repository->update($request->all(), $id);

            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'User updated.',
                'result'    => $User->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
    }


    public function showArticles($id)
    {
        try {

            $UserWithArticles = $this->repository->with('articles')->find($id);

            $response = [
                'code'    => Response::HTTP_OK,
                'message' => 'User articles list found.',
                'result'  => new UsersCollection($UserWithArticles),
            ];

            if (request()->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {

            if (request()->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
    }

}
