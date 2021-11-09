<?php

namespace Xup\Core\Models\Universe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use LaravelEveTools\EveImages\Image;

class UniverseName extends Model
{

    protected $primaryKey = 'entity_id';

    protected $fillable = ['entity_id', 'name', 'category'];


    /**
     * @throws \LaravelEveTools\EveImages\Exceptions\EveImageException
     */
    public function getAvatarUrl(int $size): string
    {
        return (new Image(Str::plural($this->category), $this->getKey(), $size))->url();
    }

}