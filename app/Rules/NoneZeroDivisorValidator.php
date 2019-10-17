<?php
namespace App\Rules;
use Illuminate\Validation\Validator as LaravelValidator;

class NoneZeroDivisorValidator extends LaravelValidator
{
    public function validateZeroDivisor($attribute, $value, $parameters)
    {
        return 0 !== $value;
    }
}
