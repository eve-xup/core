<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Model;


/**
 * @property int id
 * @property int fleet_id
 * @property string name
 */
class Wing extends Model
{

    protected $fillable = ['id', 'fleet_id', 'name'];
    public $incrementing = false;

    public function fleet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fleet::class, 'fleet_id', 'fleet_id');
    }


    public function squads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Squad::class, 'wing_id', 'id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FleetMember::class, 'wing_id', 'id');
    }



}