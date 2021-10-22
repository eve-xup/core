<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;
use Xup\Core\Traits\IsReadOnly;

class SystemGate extends Model
{
    use IsReadOnly;

    public $incrementing = false;

    protected $table = 'mapSolarSystemJumps';


    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(System::class, 'fromSolarSystemID', 'system_id');
    }

    public function destination(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(System::class, 'toSolarSystemID', 'system_id');
    }
}