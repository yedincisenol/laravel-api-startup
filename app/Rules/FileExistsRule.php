<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Storage;

class FileExistsRule implements Rule
{
    private $remoteFile = false;

    /**
     * Create a new rule instance.
     *
     * @param bool $remoteFile
     */
    public function __construct($remoteFile = false)
    {
        $this->remoteFile = $remoteFile;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_null($value)) {
            return true;
        }

        if ($this->remoteFile) {
            try {
                file_get_contents($value);

                return true;
            } catch (\Exception $e) {
            }

            return false;
        }

        return Storage::disk('s3')->exists(urldecode($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('exception.file_not_found');
    }
}
