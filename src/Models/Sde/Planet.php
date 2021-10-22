<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Traits\IsReadOnly;

class Planet extends Model
{
    use IsReadOnly;

    public $incrementing = false;

    protected $table = 'planets';

    protected $primaryKey = 'planet_id';

}