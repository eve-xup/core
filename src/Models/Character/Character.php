<?php

namespace Xup\Core\Models\Character;

use Illuminate\Database\Eloquent\Model;
use LaravelEveTools\EveImages\Image;
use Xup\Core\Models\Universe\UniverseName;

class Character extends Model
{
    protected $primaryKey = 'character_id';

    public function corporation()
    {
        return $this->hasOne(UniverseName::class, 'entity_id', 'corporation_id')
            ->where('category', 'corporation');
    }

    public function alliance()
    {
        return $this->hasOne(UniverseName::class, 'entity_id', 'alliance_id')
            ->where('category', 'alliance');
    }


    /**
     * Returns Url to Character Portait
     * @throws \LaravelEveTools\EveImages\Exceptions\EveImageException
     */
    public function getAvatarUrl(int $size): string
    {
        return (new Image('characters', $this->getKey(), $size))->url();
    }

}