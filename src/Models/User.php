<?php

namespace Xup\Core\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LaravelEveTools\EveApi\Models\RefreshToken;
use Xup\Core\Models\Access\Role;
use Xup\Core\Models\Character\Character;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable, Authorizable;


    protected $fillable = ['main_character_id'];

    protected $casts = [
        'administrator' => 'boolean'
    ];

    public function refresh_tokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RefreshToken::class, 'user_id', 'id');
    }

    public function main_character(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Character::class, 'character_id', 'main_character_id');
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongstoMany(Role::class);
    }

    public function characters(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Character::class,
            RefreshToken::class,
            'user_id',
            'character_id',
        );
    }

    public function isAdmin(){
        return $this->admin;
    }
}