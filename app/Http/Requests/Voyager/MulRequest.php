<?php


namespace App\Http\Requests\Voyager;

use App\Http\Requests\AbstractRequest;

class MulRequest extends AbstractRequest
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
            'num2' => 'required|numeric',
        ];
    }
}
