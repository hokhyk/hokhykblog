<?php

namespace App\Http\Controllers\Voyager\V1;

use App\Http\Requests\Voyager\DivRequest;
use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Response;
use App\Services\Voyager\IDivService;
use App\Validators\Voyager\DivValidator;
use App\Rules\NoneZeroDivisorRule;

class DivController extends BaseController
{
//    protected $validator;

//    protected $divisorValidator;

    protected $divService;

//    public function __construct(DivValidator $validator, NoneZeroDivisorRule $divisorValidator, IDivService $divService)
    public function __construct(IDivService $divService)
            {
//        $this->validator  = $validator;

//        $this->divisorValidator = $divisorValidator;

        $this->divService  = $divService;
    }

    /**
     * @param DivRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function div(DivRequest $request)
    {
        try {
//            $this->validator->with($request->input())->passesOrFail();
//            $this->divisorValidator->passesOrFail($request->input('num2'));
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
        catch (Exception $e) {

            return response()->json([
                'code'   =>Response::HTTP_BAD_REQUEST,
                'message' =>$e->getMessage()
            ]);
        }
    }
}
