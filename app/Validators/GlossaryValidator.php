<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class GlossaryValidator.
 *
 * @package namespace App\Validators;
 */
class GlossaryValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'description' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required',
            'description' => 'required',
        ],
    ];
}
