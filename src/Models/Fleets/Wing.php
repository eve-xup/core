<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Model;

class Wing extends Model
{

    protected $fillable = ['wing_id', 'fleet_id', 'name'];

    public function fleet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fleet::class, 'fleet_id', 'fleet_id');
    }



}