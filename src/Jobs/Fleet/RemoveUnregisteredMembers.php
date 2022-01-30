<?php


namespace Xup\Core\Jobs\Fleet;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Xup\Core\Jobs\Fleet\KickMember;
use Xup\Core\Models\Fleets\Fleet;
use Xup\Core\Models\Fleets\FleetMember;

class RemoveUnregisteredMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //public $queue = 'fleet';

    public $tags = ['fleet', 'character'];

    protected Fleet $fleet;

    public function __construct(Fleet $fleet){
        $this->fleet = $fleet;
        $this->queue = 'fleet';
    }

    public function handle(){
        $members = FleetMember::noFleetInvitation()->fleet($this->fleet->fleet_id)->get();

        $members->each(function(FleetMember $member){
            KickMember::dispatch($this->fleet->fleet_id, $member->character_id, $this->fleet->fleet_boss->refresh_token);
        });
    }
}
