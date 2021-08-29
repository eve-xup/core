<?php

namespace Xup\Core\Models\Universe;

use Illuminate\Database\Eloquent\Model;

class UniverseName extends Model
{

    protected $fillable = ['entity_id', 'name', 'category'];

}