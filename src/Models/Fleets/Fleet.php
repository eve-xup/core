<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xup\Core\Models\Character\Character;
use Xup\Core\Models\User;


/**
 * @property int fleet_id
 * @property int boss_id
 * @property string title
 * @property bool tracking
 * @property int invite_mode
 * @property bool $kick_unregistered
 *
 * @property Character fleet_boss
 * @property Collection wings
 * @property Collection squads
 */
class Fleet extends Model
{
    use SoftDeletes;

    const FLEET_CLOSED = null;
    const INVITES_APPROVAL = 2;
    const INVITES_OPEN = 3;

    const InviteTypes = [
        ['value' => null, 'text' => 'Fleet Closed'],
        ['value' => 2, 'text' => 'Requires Approval'],
        ['value' => 3, 'text' => 'Fleet Open'],
    ];

    protected $primaryKey = 'fleet_id';
    public $incrementing = false;

    public $casts = [
        'kick_unregistered' => 'bool'
    ];


    protected $fillable = ['fleet_id', 'boss_id', 'title', 'invite_mode', 'kick_unregistered', 'is_free_move', 'is_registered', 'motd'];



    public function fleet_boss(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Character::class, 'boss_id', 'character_id');
    }


    public function wings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wing::class, 'fleet_id', 'fleet_id');
    }

    public function squads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Squad::class, 'fleet_id', 'fleet_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FleetMember::class, 'fleet_id', 'fleet_id');
    }

    public function scopeInFleet(Builder $builder){
        $character_id = auth()->user()->characters->pluck('character_id');

        return $builder->whereHas('members', function(Builder $query) use ($character_id){
            $query->whereIn('character_id', $character_id);
        })->orWhereIn('boss_id', $character_id);
    }

}
