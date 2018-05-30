<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationException;
use Dingo\Api\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator->errors());
    }
}
