<?php

namespace App\Http\Controllers\Voyager\V1;

use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Response;
use App\Services\Voyager\IDivService;
use App\Validators\Voyager\DivValidator;
use App\Rules\NoneZeroDivisorRule;

class DivController extends BaseController
{
    protected $validator;

    protected $divisorValidator;

    protected $divService;

    public function __construct(DivValidator $validator, NoneZeroDivisorRule $divisorValidator, IDivService $divService)
    {
        $this->validator  = $validator;

        $this->divisorValidator = $divisorValidator;

        $this->divService  = $divService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function div(Request $request)
    {
        try {
            $this->validator->with($request->input())->passesOrFail();
            $this->divisorValidator->passesOrFail($request->input('num2'));
            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'Division is successful.',
                'result'    => $this->divService->div($request->input('num1'),$request->input('num2')),
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
