<?php

namespace Xup\Core\Models\Fleets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Xup\Core\Models\Character\Character;


class FleetMember extends Model
{
    public $incrementing = false;

    protected $primaryKey = ['character_id', 'fleet_id'];

    protected $fillable = [
        'fleet_id',
        'character_id',
        'join_time',
        'role',
        'role_name',
        'ship_type_id',
        'solar_system_id',
        'squad_id',
        'takes_fleet_warp',
        'wing_id',
    ];

    const SquadMember = 'squad_member';
    const SquadCommander = 'squad_commander';
    const WingCommander = 'wing_commander';
    const FleetCommander = 'fleet_commander';

    public function character(){
        return $this->belongsTo(Character::class, 'character_id', 'character_id');
    }

    public function invitation(){
        return $this->belongsTo(FleetInvitation::class, 'invitation_id', 'id');
    }

    public function scopeFleet(Builder $builder, $fleet_id)
    {
        return $builder->where('fleet_id', $fleet_id);
    }

    public function setJoinTimeAttribute($value)
    {
        $this->attributes['join_time'] = is_null($value) ? null : carbon($value);
    }

    public function scopeNoCharacterRecord(Builder $builder)
    {
        return $builder->whereDoesntHave('character');
    }

    public function scopeNoFleetInvitation(Builder $builder){
        return $builder->whereDoesntHave('invitation');
    }

    /**
     * Set the keys for a save update query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}

