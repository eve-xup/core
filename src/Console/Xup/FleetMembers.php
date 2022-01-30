<?php


use Xup\Core\Models\Fleets\Fleet;

class FleetMembers extends \Illuminate\Console\Command
{

    protected $signature = 'xup:fleet:members
                            {--fleet=*: Fleet Id}';

    protected $description = 'Update fleets member list';

    public function handle(){
        $fleet_id = $this->options('fleet');
        if(is_null($fleet_id)){
            //Update all fleets
            $fleets = Fleet::all();
            $fleets->each(function(Fleet $fleet){
               \Xup\Core\Jobs\Fleet\GetFleetMembers::dispatch($fleet);
            });
        }else{
            $fleet = Fleet::where('fleet_id', $fleet)->firstOrFail();
            \Xup\Core\Jobs\Fleet\GetFleetMembers::dispatch($fleet);
        }

    }

}
