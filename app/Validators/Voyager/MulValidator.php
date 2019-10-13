<?php

namespace App\Validators\Voyager;

use \Prettus\Validator\LaravelValidator;

/**
 * Class MulValidator
 * @package App\Validators\Voyager
 */
class MulValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
            'num1' => 'required|numeric',
            'num2' => 'required|numeric',
    ];

    protected $messages = [
        'num1.required' => 'Num1 should not be empty.',
        'num1.numeric' => 'Num1 should be numeric.',
        'num2.required' => 'Num2 should not be empty.',
        'num2.numeric' => 'Num2 should be numeric.',
    ];
}
