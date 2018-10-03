<?php

namespace App\Http\Requests;

use yedincisenol\UserProviderFacebook\FacebookTokenValidationRule;
use yedincisenol\UserProviderGoogle\GoogleTokenValidationRule;
use yedincisenol\UserProviderLinkedin\LinkedinTokenValidationRule;
use yedincisenol\UserProviderTwitter\TwitterTokenValidationRule;

class ConnectRequest extends Request
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
        $rules = [
            'provider'          => 'required|in:facebook,twitter,linkedin,google',
            'access_token'      => ['required'],
            'provider_user_id'  => 'required',
        ];

        if ($this->get('provider') == 'facebook') {
            $rules['access_token'][] = new FacebookTokenValidationRule();
        }

        if ($this->get('provider') == 'google') {
            $rules['access_token'][] = new GoogleTokenValidationRule();
        }

        if ($this->get('provider') == 'twitter') {
            $rules['access_token'][] = new TwitterTokenValidationRule();
        }

        if ($this->get('provider') == 'linkedin') {
            $rules['access_token'][] = new LinkedinTokenValidationRule();
        }

        return $rules;
    }
}
