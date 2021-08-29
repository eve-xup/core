<?php

namespace Xup\Core\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEveTools\EveApi\Models\RefreshToken;
use Xup\Core\Models\Character\Character;

class User extends Model
{

    public function refresh_tokens()
    {
        $this->hasMany(RefreshToken::class, 'user_id', 'id');
    }

    public function main_character(){
        $this->hasOne(Character::class, 'character_id', 'main_character_id');
    }
}