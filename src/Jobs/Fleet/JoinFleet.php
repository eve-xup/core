<?php


namespace Xup\Core\Jobs\Fleet;


use LaravelEveTools\EveApi\Jobs\Fleets\InviteMember;

use Xup\Core\Models\Fleets\Fleet;
use Xup\Core\Models\Fleets\FleetInvitation;

class JoinFleet extends InviteMember
{

    public $queue = 'fleet';

    protected string $role = 'squad_member';

    protected int $character_id;

    protected Fleet $fleet;

    public function __construct(Fleet $fleet, int $character_id)
    {
        parent::__construct($fleet->fleet_id, $fleet->fleet_boss->refresh_token);
        $this->character_id = $character_id;
        $this->fleet=$fleet;
    }

    public function handle()
    {
        $wing = $this->fleet->wings->first();
        $squad = $wing->squads->first();

        $this->request_body = [
            'role' => $this->role,
            'wing_id' => $wing->id,
            'squad_id' => $squad->id,
            'character_id' => $this->character_id
        ];

        $response = $this->retrieve();
        //dd($response);
        FleetInvitation::firstOrCreate([
            'fleet_id' => $this->getFleetId(),
            'character_id' => $this->character_id
        ]);
    }
}
