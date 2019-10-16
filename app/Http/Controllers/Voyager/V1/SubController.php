<?php

namespace App\Http\Controllers\Voyager\V1;

use App\Http\Requests\Voyager\SubRequest;
//use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Response;
use Exception;
use App\Services\Voyager\ISubService;
use App\Validators\Voyager\SubValidator;

/**
 * Class SubController
 * @package App\Http\Controllers\Voyager\V1
 */
class SubController extends BaseController
{
    protected $validator;
    protected $subService;

    /**
     * SubController constructor.
     * @param SubValidator $validator
     * @param ISubService $subService
     */
//    public function __construct(SubValidator $validator, ISubService $subService)
    public function __construct(ISubService $subService)
    {
//        $this->validator  = $validator;
        $this->subService  = $subService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function sub(SubRequest $request)
    {
        try {
//            $this->validator->with( $request->input())->passesOrFail();
            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'Subtraction is successful.',
                'result'    => $this->subService->sub($request->input('num1'),$request->input('num2')),
            ];

            if (request()->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        }
        catch (Exception $e) {

            return response()->json([
                'code'   =>Response::HTTP_BAD_REQUEST,
                'message' =>$e->getMessage()
            ]);
        }
    }
}
