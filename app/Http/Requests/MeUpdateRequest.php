<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class MeUpdateRequest extends Request
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
            'username'      => 'nullable|min:3|unique:users,username,'.$this->user()->id,
            'name'          => 'required',
            'email'         => [
                Rule::unique('users', 'email')->ignore($this->user()->id, 'id'),
            ],
        ];
    }
}
