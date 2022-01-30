<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\Wings;
use Xup\Core\Models\Fleets\Fleet;
use Xup\Core\Models\Fleets\Squad;
use Xup\Core\Models\Fleets\Wing;

class GetWings extends Wings
{

    public $queue = 'fleet';

    protected Fleet $fleet;

    public function __construct(Fleet $fleet)
    {
        parent::__construct($fleet->fleet_id, $fleet->fleet_boss->refresh_token);
    }

    public function handle()
    {
        $fleet = Fleet::findOrFail($this->getFleetId());

        $response = $this->retrieve();

        if($response->isCachedLoad()){
            //return;
        }


        collect($response)->each(function($esi_wing) use ($fleet){

            $wing = Wing::firstOrNew([
                'id'=>$esi_wing->id
            ]);

            $wing->fill([
                'fleet_id'=>$fleet->getKey(),
                'name' => $esi_wing->name,
            ])->save();

            collect($esi_wing->squads)->each(function($esi_squad) use ($wing){

                $squad = Squad::firstOrNew([
                    'id' => $esi_squad->id
                ]);

                $squad->fill([
                    'name'=>$esi_squad->name,
                    'wing_id'=>$wing->getKey(),
                    'fleet_id'=>$wing->fleet_id,
                ])->save();

            });
        });

    }
}
