<?php

namespace Xup\Core\Models\Access;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Models\User;

class Role extends Model
{

    protected $fillable = ['title', 'description'];

    public $timestamps = false;


    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}