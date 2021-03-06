<?php

namespace Xup\Core\Settings;

use Illuminate\Support\Facades\Cache;
use Xup\Core\Exceptions\SettingsException;

abstract class Settings
{

    protected static $prefix;

    protected static $model;

    protected static $defaults = [];

    protected static $scope = 'global';

    public static function get_key_prefix($name, $for_id): string
    {
        if(is_null(static::$prefix))
            throw new SettingsException("No prefix defined");

        if(static::$scope != 'global')
            return implode('.', [$for_id, static::$prefix, $name]);

        return implode('.', [static::$prefix, $name]);
    }

    public static function get_affected_id($for_id)
    {
        if(!auth()->check())
            return $for_id;

        if(is_null($for_id)){
            return auth()->user()->getAuthIdentifier();
        }

        return $for_id;
    }

    /**
     * @throws SettingsException
     */
    public static function get($name, $for_id = null)
    {
        return Cache::rememberForever(self::get_key_prefix($name,
         self::get_affected_id($for_id)), function() use ($name, $for_id){

            $value = (new static::$model);

            if(static::$scope != 'global')
                $value = $value->where('user_id', self::get_affected_id($for_id));

            $value = $value->where('name', $name)
                ->value('value');


            if($value)
                return json_decode($value);

            if(array_key_exists($name, static::$defaults))
                return json_decode(json_encode(static::$defaults[$name]));

            return null;
        });
    }

    /**
     * @throws SettingsException
     */
    public static function set($name, $value, $for_id = null)
    {
        $db = (new static::$model);

        if(static::$scope != 'global')
            $db = $db->where('user_id', self::get_affected_id($for_id));

        $db = $db->where('name', $name)->first();

        $encoded_value = json_encode($value, JSON_NUMERIC_CHECK);

        if(!$db){
            $db = new static::$model;
        }

        $db->fill([
            'name'=>$name,
            'value' => $encoded_value,
        ]);

        if(static::$scope != 'global')
            $db->user_id = self::get_affected_id($for_id);

        $db->save();

        Cache::forever(self::get_key_prefix($name, self::get_affected_id($for_id)), $value);

    }
}