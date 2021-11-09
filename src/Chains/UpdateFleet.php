<?php

namespace Xup\Core\Chains;

use Illuminate\Support\Facades\Bus;
use LaravelEveTools\EveApi\Models\RefreshToken;
use Xup\Core\Jobs\Fleet\GetFleetInformation;
use Xup\Core\Jobs\Fleet\GetFleetMembers;
use Xup\Core\Jobs\Fleet\GetWings;

class UpdateFleet
{
    public $token;
    public $fleet_id;

    public function __construct($fleet_id, RefreshToken $token)
    {
        $this->fleet_id = $fleet_id;
        $this->token = $token;
    }

    public function build(){
        return Bus::chain([
            new GetFleetInformation($this->fleet_id, $this->token),
            new GetWings($this->fleet_id, $this->token),
            new GetFleetMembers($this->fleet_id, $this->token),
        ]);
    }

    public function fire(){
        $this->build()->dispatch();
    }
}