<?php


namespace Xup\Core\Observers;


use Xup\Core\Models\Fleets\FleetInvitation;

class FleetMemberObserver
{

    /**
     * If the new member record has an invitation for this fleet
     * Register the invitation with the member.
     * @param $member
     */
    public function created($member){
        $invitation = FleetInvitation::where('fleet_id', $member->fleet_id)->where('character_id', $member->character_id)->first();

        if(!is_null($invitation)){
            $member->invitation_id = $invitation->getKey();
            $member->save();
        }

    }

}
