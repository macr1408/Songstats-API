<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['email', 'api_token'];
}
