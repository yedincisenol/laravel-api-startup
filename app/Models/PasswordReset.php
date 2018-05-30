<?php

namespace App\Models;

class PasswordReset extends Model
{
    /**
     * primaryKey.
     *
     * @var int
     */
    protected $primaryKey = 'email';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = ['email', 'token', 'user_id'];

    public function setUpdatedAt($value)
    {
    }

    public function getUpdatedAtColumn()
    {
    }
}
