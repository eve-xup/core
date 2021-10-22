<?php

namespace Xup\Core\Models\Access;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $fillable = ['title'];

    public $timestamps = false;


    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

}