<?php

namespace App\Validators\Blog;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ArticleValidator.
 *
 * @package namespace App\Validators\Blog;
 */
class ArticleValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required|max:255',
//            'author'=> 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required|max:255',
        ],
    ];

    protected $messages = [
        'title.required' => 'Article title should not be empty.',
        'title.max' => 'Article title is too long(255 characters at most.',
    ];
}
