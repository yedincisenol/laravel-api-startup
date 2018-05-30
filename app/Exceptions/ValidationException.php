<?php

namespace App\Exceptions;

use Dingo\Api\Exception\StoreResourceFailedException;

class ValidationException extends StoreResourceFailedException
{
    /**
     * Create a new validation exception instance.
     *
     * @param \Illuminate\Support\MessageBag|array $errors
     *
     * @return void
     */
    public function __construct($errors)
    {
        $errorText = ':';
        if (is_object($errors)) {
            foreach ($errors->getMessages() as $error) {
                foreach ($error as $e) {
                    $errorText .= ' '.$e;
                }
            }
        }

        parent::__construct(trans('exception.validation_failed').$errorText, $errors, null, [], 0);
    }
}
