<?php

namespace Xup\Core\Jobs\Character;

use LaravelEveTools\EveApi\Jobs\Fleets\Character as FleetJob;

class Fleet extends FleetJob
{

    /**
     * @var null
     */
    public $details = null;

    public $queue = 'fleet';

    /**
     * @throws \Seat\Eseye\Exceptions\InvalidContainerDataException
     * @throws \Seat\Eseye\Exceptions\RequestFailedException
     * @throws \LaravelEveTools\EveApi\Exceptions\UnavailableEveServerException
     * @throws \Seat\Eseye\Exceptions\InvalidAuthenticationException
     * @throws \LaravelEveTools\EveApi\Exceptions\PermanentInvalidTokenException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle()
    {
        $response = $this->retrieve();
        if($response->fleet_boss_id === $this->getCharacterId()){
            //$key = "fleet.create.{$this->getCharacterId()}.fleet_id";
            //cache()->remember($key, 120, $response->fleet_id);
            //cache("fleet.create.{$this->getCharacterId()}.fleet_id", $response->fleet_id);
            $this->details = [
                'fleet_id' => $response->fleet_id,
                'fleet_boss_id' => $response->fleet_boss_id,
            ];
        }

    }

    public function getResponse(){
        return $this->details;
    }
}
