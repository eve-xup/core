<?php

namespace Xup\Core\Settings;

use Xup\Core\Models\Settings\GlobalSetting;

class Xup extends Settings
{

    /* Option settings */
    public static $options = [

    ];

    protected static $prefix = 'xup';

    protected static $model = GlobalSetting::class;

    protected static $scope = 'global';

    protected static $defaults = [

    ];

}