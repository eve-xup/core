<?php

namespace Xup\Core\Listeners;



use Illuminate\Auth\Events\Login;
use Xup\Core\Models\Character\Character;
use Xup\Core\Models\User;

class UpdateCharacterDetails
{

    /**
     * After a user is authenticated, get refresh character details form esi
     *
     * @param User $user
     * @return void
     */
    public function handle(Login $event){

        $event->user->characters->each(function(Character $character){
            \Xup\Core\Jobs\Character\Character::dispatch($character->character_id);
        });
    }
}