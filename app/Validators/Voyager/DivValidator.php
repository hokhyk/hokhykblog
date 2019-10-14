<?php

namespace App\Validators\Voyager;

use \Prettus\Validator\LaravelValidator;
use App\Validators\Voyager\BasicCalculationValidator as BaseValidator;

//use App\Rules\NoneZeroDivisorRule;


class DivValidator extends BaseValidator
{
//    protected $rules = [
//            'num1' => 'required|numeric',
////            'num2' => 'required|numeric',
//Todo:  Here should write a Laravel's customized validator for zoro divisor validation. Followed Jeffery's way but failed as here we're using \Prettus\Validator\LaravelValidator. It's different.
// Need to use Validator::extend() method to create a new validation rule. Do it later. At the moment I just injected a new validator into the controller for temporary implementation.
//            'num2' => [new NoneZeroDivisorRule()],
//    ];
//
//    protected $messages = [
//        'num1.required' => 'Num1 should not be empty.',
//        'num1.numeric' => 'Num1 should be numeric.',
//        'num2.required' => 'Num2 should not be empty.',
//        'num2.numeric' => 'Num2 should be numeric.',
//    ];
}
