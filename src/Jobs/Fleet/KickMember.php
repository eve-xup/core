<?php


namespace Xup\Core\Jobs\Fleet;


use LaravelEveTools\EveApi\Jobs\Fleets\KickMember as KickMemberJob;
use Xup\Core\Models\Fleets\FleetMember;

class KickMember extends KickMemberJob
{

    public $queue = 'fleet';


    public function handle()
    {
        $data = $this->retrieve();

        $member = FleetMember::where('fleet_id', $this->getFleetId())->where('character_id', $this->getMemberId())->delete();
    }
}
