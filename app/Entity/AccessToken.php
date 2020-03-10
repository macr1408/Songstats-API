<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $table = 'access_tokens';
    public $timestamps = false;
    protected $fillable = ['user_id', 'access_token', 'refresh_token', 'expires_in', 'scope', 'site'];
}
