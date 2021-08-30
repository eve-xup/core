<?php

namespace Xup\Core\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use LaravelEveTools\EveApi\Models\RefreshToken;
use Xup\Core\Models\Character\Character;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;


    protected $fillable = ['main_character_id'];

    public function refresh_tokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RefreshToken::class, 'user_id', 'id');
    }

    public function main_character(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Character::class, 'character_id', 'main_character_id');
    }
}