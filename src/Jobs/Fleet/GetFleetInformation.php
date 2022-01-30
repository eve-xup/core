<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\Fleet as FleetJob;
use LaravelEveTools\EveApi\Models\RefreshToken;
use Xup\Core\Models\Fleets\Fleet;

class GetFleetInformation extends FleetJob
{

    public $queue = 'fleet';

    protected Fleet $fleet;

    /**
     * GetFleetInformation constructor.
     * @param Fleet $fleet
     */
    public function __construct(Fleet $fleet)
    {
        $this->fleet = $fleet;
        parent::__construct($fleet->fleet_id, $fleet->fleet_boss->refresh_token);
    }

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
