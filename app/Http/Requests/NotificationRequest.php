<?php

namespace App\Http\Requests;

class NotificationRequest extends Request
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
            'device_id'                 =>  'required|string',
            'notification_body'         =>  'required|string'
        ];
    }
}
