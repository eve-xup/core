<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Traits\IsReadOnly;

class InvGroup extends Model
{
    use IsReadOnly;

    public $incrementing = false;

    protected $table = 'invGroups';

    protected $primaryKey = 'groupID';
}