<?php

namespace Xup\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    public function boot(){

        $this->add_migrations();

    }

    private function add_migrations(){
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

}