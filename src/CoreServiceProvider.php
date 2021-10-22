<?php

namespace Xup\Core;

use Xup\Core\Console\Commands\Sde\Update;

class CoreServiceProvider extends AbstractPluginProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->add_commands();
    }

    public function register()
    {

    }


    public function add_commands(){
        $this->commands([
            Update::class,
        ]);
    }

}