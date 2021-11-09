<?php

namespace Xup\Core\Observers;


use Xup\Core\Chains\UpdateFleet;
use Xup\Core\Jobs\Fleet\GetFleetInformation;
use Xup\Core\Jobs\Fleet\GetFleetMembers;
use Xup\Core\Jobs\Fleet\GetWings;
use Xup\Core\Models\Fleets\Fleet;

class FleetObserver
{

    public function created(Fleet $fleet){

        (new UpdateFleet($fleet->fleet_id, $fleet->fleet_boss->refresh_token))->fire();

    }

}