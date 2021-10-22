<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Traits\IsReadOnly;

class Region extends Model
{
    use IsReadOnly;

    public $incrementing = false;

    protected $table = 'regions';

    protected $primaryKey = 'region_id';


    public function constellations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Constellation::class, 'region_id', 'region_id');
    }

    public function systems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(System::class, 'region_id', 'region_id');
    }

    public function planets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Planet::class, 'region_id', 'region_id');
    }

    public function moons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Moon::class, 'region_id', 'region_id');
    }
}