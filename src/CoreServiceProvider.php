<?php

namespace Xup\Core;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Xup\Core\Console\Commands\Sde\Update;
use Xup\Core\Listeners\UpdateCharacterDetails;
use Xup\Core\Models\Fleets\Fleet;
use Xup\Core\Models\Fleets\FleetMember;
use Xup\Core\Observers\FleetMemberObserver;
use Xup\Core\Observers\FleetObserver;

class CoreServiceProvider extends AbstractPluginProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->add_commands();

        $this->register_observers();

    }

    public function register()
    {
        $this->register_events();

        $this->register_observers();
    }


    public function register_observers()
    {
        Fleet::observe(FleetObserver::class);
        FleetMember::observe(FleetMemberObserver::class);
    }


    public function add_commands(){
        $this->commands([
        ]);
    }

    public function register_events(){
        Event::listen(Login::class, [UpdateCharacterDetails::class, 'handle']);
    }

}
