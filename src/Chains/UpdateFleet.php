<?php

namespace Xup\Core\Chains;

use Illuminate\Support\Facades\Bus;
use LaravelEveTools\EveApi\Models\RefreshToken;
use Xup\Core\Jobs\Fleet\GetFleetInformation;
use Xup\Core\Jobs\Fleet\GetFleetMembers;
use Xup\Core\Jobs\Fleet\GetWings;
use Xup\Core\Models\Fleets\Fleet;
use Xup\Web\Broadcasts\FleetSynced;

class UpdateFleet
{

    public Fleet $fleet;

    public function __construct(Fleet $fleet)
    {
        $this->fleet = $fleet;

    }

    public function build(){
        return Bus::chain([
            new GetFleetInformation($this->fleet),
            new GetWings($this->fleet),
            new GetFleetMembers($this->fleet),
            //new FleetSynced($this->fleet_id),
        ]);
    }

    public function fire(){
        $this->build()->dispatch();
    }
}
