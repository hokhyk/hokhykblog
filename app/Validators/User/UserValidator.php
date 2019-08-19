<?php

namespace App\Validators\User;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class UserValidator.
 *
 * @package namespace App\Validators\User;
 */
class UserValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required|min:2|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'unique:users,phone',
            'password' => 'required|min:6',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users,email',
            'phone' => 'unique:users,phone',
            'password' => 'required|min:6',
//            'role_id' => 'required|integer',
        ],
    ];

    protected $messages = [

    ];
}
