<?php

namespace App\Http\Requests;

class EmailVerifyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Data validation.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'code'  => 'required|numeric'
        ];
    }
}