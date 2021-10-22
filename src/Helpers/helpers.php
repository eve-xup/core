<?php


if(!function_exists('settings')){


    /**
     * @throws \Xup\Core\Exceptions\SettingsException
     */
    function settings($name, bool $global = false)
    {
        if(is_array($name)){
            if(count($name) < 2)
                throw new \Xup\Core\Exceptions\SettingsException("must provide a name and a value");

            $for_id = $name[2] ?? null;

            if($global){
                return \Xup\Core\Settings\Xup::set($name[0], $name[1], $for_id);
            }

            //Return profile setting
        }

        if($global)
            return \Xup\Core\Settings\Xup::get($name);

        ///return profile setting
    }

}