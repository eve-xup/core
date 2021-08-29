<?php

namespace Xup\Core\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEveTools\Modesl\RefreshToken;

class User extends Model
{


    public function refresh_tokens()
    {
        $this->hasMany(RefreshToken::class, 'user_id', 'id');
    }
}