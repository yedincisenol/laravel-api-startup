<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    =>  'required|exists:users,email'
        ];
    }

    public function messages()
    {
        return [
            'email.exists' =>  trans('user.user_not_found_with_this_email')
        ];
    }
}
