<?php

namespace Xup\Core\Jobs\Fleet;

use LaravelEveTools\EveApi\Jobs\Fleets\Fleet;

class GetFleetInformation extends Fleet
{

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
        $fleet = $this->retrieve();

        dd($fleet->raw);
    }
}