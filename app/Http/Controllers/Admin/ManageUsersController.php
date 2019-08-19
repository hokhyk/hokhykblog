<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller as BaseController;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Validators\User\UserValidator;
use Illuminate\Http\Response;
use Exception;
use App\Repositories\Eloquent\User\UserRepositoryEloquent as UserRepository;


/**
 * Class ManageUsersController.
 *
 * @package namespace App\Http\Controllers\User;
 */
class ManageUsersController extends BaseController
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
     * ManageUsersController constructor.
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $users = $this->repository->all();
//        $users = $this->repository->paginate($limit = null, $columns = ['*']);

        $response = [
            'code' => Response::HTTP_OK,
            'message' => 'Users retrived.',
            'result'    => $users,
        ];

        if (request()->wantsJson()) {

            return response()->json($response);
        }

        return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(UserCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $user = $this->repository->create($request->all());

            $response = [
                'code' => Response::HTTP_CREATED,
                'message' => 'User created.',
                'result'    => $user,
            ];

            if ($request->wantsJson()) {

                return response()->json($response, Response::HTTP_CREATED );
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
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
                'result'  => $user,
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

            $user = $this->repository->update($request->all(), $id);

            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'User updated.',
                'result'    => $user->toArray(),
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->repository->delete($id);

            if (request()->wantsJson()) {

                return response()->json(['code' => Response::HTTP_OK, 'message' => 'User is deleted.', 'deleted' => $deleted]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (Exception $exception) {

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'Not an user found.']);
        }
    }
}
