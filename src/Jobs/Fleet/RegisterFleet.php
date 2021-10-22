<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\Character;
use Xup\Core\Models\Fleets\Fleet;

class RegisterFleet extends Character
{

    public function handle()
    {
        $data = $this->retrieve();

        $fleet = Fleet::firstOrNew([
            'fleet_id'=>$data->fleet_id,
        ])->fill([
            'boss_character_id'=>$this->getCharacterId()

        ])->save();

    }
}