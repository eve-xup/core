<?php

namespace Xup\Core\Models\Character;

use Illuminate\Database\Eloquent\Model;
use LaravelEveTools\EveApi\Models\RefreshToken;
use LaravelEveTools\EveImages\Image;
use Xup\Core\Models\Universe\UniverseName;


/**
 * @property string name
 * @property int character_id
 * @property int chorporation_id
 * @property int alliance_id
 * @property double security_stats
 *
 * @property UniverseName corporation
 * @property UniverseName alliance
 * @property RefreshToken refresh_token
 *
 */
class Character extends Model
{

    protected $primaryKey = 'character_id';

    protected $fillable = [ 'character_id', 'name', 'corporation_id','alliance_id', 'security_status'];

    public function corporation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UniverseName::class, 'entity_id', 'corporation_id');
    }

    public function alliance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UniverseName::class, 'entity_id', 'alliance_id');

    }

    public function refresh_token(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RefreshToken::class, 'character_id', 'character_id');
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