<?php


namespace App\Http\Requests\Voyager;

use App\Http\Requests\AbstractRequest;
use App\Rules\NoneZeroDivisorRule;

class DivRequest extends AbstractRequest
{
public function authorize() {
        return true;
}
    /**
     * @return  array
     */
    public function rules()
    {
        return [
            'num1' => 'required|numeric',
            // Todo: Laravel's Validator class parser function does not support this kind of arrays.
            // There're two ways to solve this problem.
            // 1. include required and numeric in the NoneZeroDivisorRule class. Not good, what if there are quite a lot of validation rules?
            // 2. Follow Laravel's traditional Validator::extend() method to define a rule separately and name it maybe 'zerodividor' and given the expression as 'num2' => 'required/numeric/zerodividor'.
            'num2' => 'required|numeric|ZeroDivisor',
//            'num2' => new NoneZeroDivisorRule(),
        ];
    }
}
