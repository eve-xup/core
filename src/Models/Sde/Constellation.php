<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Traits\IsReadOnly;

class Constellation extends Model
{
    use IsReadOnly;

    public $incrementing = false;

    protected $table = 'constellations';

    protected $primaryKey = 'constellation_id';


    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function systems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(System::class, 'constellation_id', 'constellation_id');
    }

    public function planets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Planet::class, 'constellation_id', 'constellation_id');
    }

    public function moons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Moon::class, 'constellation_id', 'constellation_id');
    }
}