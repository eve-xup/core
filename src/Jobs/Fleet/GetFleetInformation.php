<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\Fleet as FleetJob;
use Xup\Core\Models\Fleets\Fleet;

class GetFleetInformation extends FleetJob
{

    public $queue = 'fleet';

    /**
     * @throws \Seat\Eseye\Exceptions\InvalidContainerDataException
     * @throws \Seat\Eseye\Exceptions\RequestFailedException
     * @throws \LaravelEveTools\EveApi\Exceptions\UnavailableEveServerException
     * @throws \LaravelEveTools\EveApi\Exceptions\PermanentInvalidTokenException
     * @throws \Seat\Eseye\Exceptions\InvalidAuthenticationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle()
    {
        $response = $this->retrieve();

        $fleet = Fleet::findOrFail($this->getFleetId());

        $fleet->update([
            'is_free_move' => $response->is_free_move,
            'is_registered' => $response->is_registered,
            'motd' => $response->motd
        ]);

    }
}