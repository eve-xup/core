<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Model;

class FleetInvitation extends Model
{
    public $incrementing = false;


    protected $fillable = ['fleet_id', 'character_id'];


}
