<?php

namespace Xup\Core;

use Illuminate\Support\ServiceProvider;

class AbstractPluginProvider extends ServiceProvider
{

    public function registerPermissions(string $path, string $scope = 'global', $policy = null){
        $key = sprintf('xup.permissions.%s', $scope);
        $this->mergeConfigFrom($path, $key);

        if(!is_null($policy)){
            config()->set(
                sprintf('%s.permission-policies.%', 'xup', $scope),
                $policy
            );
        }
    }

    public function registerCSSFile(string $path){
        $key = sprintf('%s.cssfiles', 'xup');

        config()->set($key, array_merge(
            [$path],
            config()->get($key, [])
        ));
    }

    public function getPermissions(){
        return config(sprintf('%s.permissions', 'xup'), []);
    }


    /**
     * @param string $path
     * @param string $scope
     */
    public function registerNavigation(string $path)
    {
        $key = sprintf('%s.navigation.application', 'xup');
        $this->mergeConfigFrom($path, $key);
    }

}