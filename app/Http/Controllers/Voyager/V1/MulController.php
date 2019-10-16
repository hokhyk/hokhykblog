<?php

namespace App\Http\Controllers\Voyager\V1;

use App\Http\Requests\Voyager\MulRequest;
use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Response;
use Exception;
use App\Services\Voyager\IMulService;
use App\Validators\Voyager\MulValidator;

/**
 * Class MulController
 * @package App\Http\Controllers\Voyager\V1
 */
class MulController extends BaseController
{
//    protected $validator;
    protected $mulService;

    /**
     * MulController constructor.
     * @param MulValidator $validator
     * @param IMulService $mulService
     */
//    public function __construct(MulValidator $validator, IMulService $mulService)
    public function __construct(IMulService $mulService)
    {
//        $this->validator  = $validator;
        $this->mulService  = $mulService;
    }

    /**
     * @param MulRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mul(MulRequest $request)
    {
        try {
//            $this->validator->with( $request->input())->passesOrFail();
            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'Multiplication is successful.',
                'result'    => $this->mulService->mul($request->input('num1'),$request->input('num2')),
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
