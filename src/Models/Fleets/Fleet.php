<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Models\Character\Character;

class Fleet extends Model
{

    protected $fillable = ['fleet_id', 'boss_character_id', 'name'];



    public function fleetBoss(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Character::class, 'boss_character_id', 'character_id');
    }

    public function wings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wing::class, 'fleet_id', 'fleet_id');
    }

}