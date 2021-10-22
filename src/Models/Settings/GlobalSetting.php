<?php

namespace Xup\Core\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{


    protected $fillable = ['name', 'value'];
}