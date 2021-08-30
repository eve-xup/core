<?php

namespace Xup\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

    }

    public function register()
    {

    }


}