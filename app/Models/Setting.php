<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    public $timestamps = false;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'key', 'value', 'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
