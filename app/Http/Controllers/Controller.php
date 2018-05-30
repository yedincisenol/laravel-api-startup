<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerifyRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\ResetCodeVerifyRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ResetPasswordUpdateRequest;
use App\Models\PasswordReset;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use Response;
use App\Exceptions\ValidationException;
use Validator;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class   Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            try {
                throw new ValidationException($validator->errors());
            } catch (\Exception $e) {
                Response::make(['message' => $e->getMessage()], 422)->send();
                exit();
            }
        }
    }

    /**
     * Check username exists
     * @param Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function username(Request $request)
    {
        $username = strtolower($request->get('q'));
        if (strlen($username) < 3) {
            return $this->response->errorBadRequest(trans('user.least_3_letter'));
        }
        if (str_slug($username) != $username)
        {
            return $this->response->errorBadRequest(trans('user.username_not_valid'));
        }
        if (User::query()->where('username', $username)->exists()) {
            return $this->response()->error(trans('user.username_in_use'), 409);
        }
        return $this->response()->noContent();
    }

    /**
     * Email verify web interface
     * @param Request $request
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function emailVerifyWeb(Request $request)
    {
        list($email, $code) = explode('|', base64_decode($request->get('q')));

        if ($this->verificationCodeValidate($email, $code)) {
            return trans('user.email_verified');
        }

        return trans('user.wrong_verification_link');
    }

    /**
     * Verify email code
     * @param $email
     * @param $code
     * @return bool
     */
    private function verificationCodeValidate($email, $code)
    {
        $user = User::query()
            ->where('email', $email)
            ->where('verification_code', $code)
            ->first();

        if (!$user) {
            return false;
        }

        $user->update([
            'verification_code' => null,
        ]);

        return true;
    }

    /**
     * Email verify
     * @param EmailVerifyRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function emailVerify(EmailVerifyRequest $request)
    {
        $valid = $this->verificationCodeValidate($request->get('email'), $request->get('code'));
        if (!$valid) {
            return $this->response->errorBadRequest(trans('user.wrong_verification_code'));
        }

        return $this->response->created();
    }

    /**
     * Resend email verification code
     * @param ResendEmailVerificationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function resendEmailVerification(ResendEmailVerificationRequest $request)
    {
        $user = User::query()
            ->where('email', $request->get('email'))
            ->first();

        if (!$user) {
            abort(404, trans('user.user_not_found_with_this_email'));
        } elseif(!$user->verification_code) {
            abort(400, trans('user.email_already_validated'));
        }

        $user->notify(new EmailVerificationNotification());

        return $this->response->created();

    }

    /**
     * Password reset request
     * @param ResetPasswordRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function resetPasswordRequest(ResetPasswordRequest $request)
    {
        $user = User::query()
            ->where('email', $request->get('email'))
            ->first();

        $reset = PasswordReset::updateOrCreate([
            'email' => $user->email
        ], [
            'token' => $this->getVerificationCode()
        ]);

        $user->notify(new ResetPasswordNotification($reset->token));

        return $this->response->created();
    }

    /**
     * Reset password
     * @param ResetPasswordUpdateRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function updatePassword(ResetPasswordUpdateRequest $request)
    {
        $passwordReset = PasswordReset::query()
            ->where('email', $request->get('email'))
            ->where('token', $request->get('code'))
            ->whereNull('user_id')
            ->first();

        if (!$passwordReset) {
            abort(422, trans('user.wrong_password_reset_code'));
        }

        if (!$user = User::query()->where('email', $request->get('email'))->first()) {
            abort(404, trans('user.user_not_found_with_this_email'));
        }

        $password = $request->get('password');

        $user->update([
            'password'  =>  bcrypt($password)
        ]);

        $passwordReset->delete();

        return $this->response->created();
    }

    /**
     * Verify reset code
     * @param ResetCodeVerifyRequest $request
     */
    public function resetCodeVerify(ResetCodeVerifyRequest $request)
    {
        $exists = PasswordReset::query()
            ->where('email', $request->get('email'))
            ->where('token', $request->get('code'))
            ->whereNull('user_id')
            ->exists();

        if (!$exists) {
            return abort(400, trans('user.verification_failed'));
        }

    }

    /**
     * Get unique verification number
     * @return int
     */
    public function getVerificationCode()
    {
        $code = rand(100000, 999999);
            $exists = User::query()->where('verification_code', $code)->first();
        if ($exists) return $this->getVerificationCode();

        return $code;
    }
}
