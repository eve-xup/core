<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;

class InvType extends Model
{

    public $incrementing = false;

    public $casts = [
        'published' => 'boolean',
    ];

    protected $table = 'invTypes';

    protected $primaryKey = 'typeID';

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InvGroup::class, 'groupID', 'groupID')
            ->withDefault([
                'groupName' => 'Unknown'
            ]);
    }
}