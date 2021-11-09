<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{

    protected $fillable = ['id', 'fleet_id', 'wing_id', 'name'];
    public $incrementing = false;

    public function fleet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fleet::class, 'fleet_id','fleet_id');
    }

    public function wing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wing::class, 'wing_id', 'wing_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FleetMember::class, 'squad_id', 'id');
    }
}