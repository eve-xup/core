<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\FleetMembers;
use Xup\Core\Jobs\Character\Character;

use Xup\Core\Models\Fleets\Fleet;
use Xup\Core\Models\Fleets\FleetMember;


class GetFleetMembers extends FleetMembers
{


    public $queue = 'fleet';

    public Fleet $fleet;

    public function __construct(Fleet $fleet)
    {
        $this->fleet = $fleet;
        parent::__construct($fleet->fleet_id, $fleet->fleet_boss->refresh_token);
    }

    public function handle()
    {
        $members = $this->retrieve();

        if($this->skipIfTrue($members)) return;

        $stored_members = FleetMember::fleet($this->getFleetId())->get()->pluck('character_id');

        $current_members = collect();

        collect($members)->each(function ($member) use ($current_members) {

            $m = FleetMember::firstOrNew([
                'fleet_id' => $this->getFleetId(),
                'character_id' => $member->character_id
            ]);

            $m->fill([
                'join_time' => $member->join_time,
                'role' => $member->role,
                'role_name' => $member->role_name,
                'ship_type_id' => $member->ship_type_id,
                'solar_system_id' => $member->solar_system_id,
                'squad_id' => $member->squad_id,
                'takes_fleet_warp' => $member->takes_fleet_warp,
                'wing_id' => $member->wing_id,
            ])->save();

            $current_members->add($m);
        });

        $non_members = $stored_members->diff($current_members->pluck('character_id'));

        if ($non_members->count() > 0) {
            FleetMember::fleet($this->getFleetId())->whereIn('character_id', $non_members)->delete();
        }

        $non_registered = FleetMember::NoCharacterRecord()->fleet($this->fleet->fleet_id)->get();

        $non_registered->each(function(FleetMember $member){
            dispatch(new Character($member->character_id, true));
        });


        RemoveUnregisteredMembers::dispatchIf($this->fleet->kick_unregistered, $this->fleet);

    }
}
