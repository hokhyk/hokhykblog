<?php

namespace App\Http\Controllers\Voyager\V1;

use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Response;
use Exception;
use App\Services\Voyager\IAddService;
use App\Validators\Voyager\AddValidator;

/**
 * Class AddController
 * @package App\Http\Controllers\Voyager\V1
 */
class AddController extends BaseController
{
    protected $validator;
    protected $addService;

    /**
     * AddController constructor.
     * @param AddValidator $validator
     * @param IAddService $addService
     */
    public function __construct(AddValidator $validator, IAddService $addService)
    {
        $this->validator  = $validator;
        $this->addService  = $addService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function add(Request $request)
    {
        try {
            $this->validator->with( $request->input())->passesOrFail();
            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'Addition is successful.',
                'result'    => $this->addService->add($request->input('num1'),$request->input('num2')),
            ];

            if (request()->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        }
        catch (ValidatorException $e) {

            return response()->json([
                'code'   =>Response::HTTP_BAD_REQUEST,
                'message' =>$e->getMessage()
            ]);
        }
    }
}
