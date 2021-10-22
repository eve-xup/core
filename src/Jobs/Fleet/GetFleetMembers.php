<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\FleetMembers;

class GetFleetMembers extends FleetMembers
{


    public function handle()
    {
        $members = $this->retrieve();

        dd($members->raw);
    }
}