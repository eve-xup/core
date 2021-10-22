<?php

namespace Xup\Core\Models\Sde;

use Illuminate\Database\Eloquent\Model;

class MapDenormalize extends Model
{

    const BELT = 9;

    const CONSTELLATION = 4;

    const MOON = 8;

    const PLANET = 7;

    const REGION = 3;

    const SUN = 6;

    const SYSTEM = 5;

    const UBIQUITOUS = 2396;

    const COMMON = 2397;

    const UNCOMMON = 2398;

    const RARE = 2400;

    const EXCEPTIONAL = 2401;

    public $incrementing = false;

    protected $table = 'mapDenormalize';

    protected $primaryKey = 'itemID';


    /**
     * @param $query
     * @return mixed
     */
    public function scopeConstellations($query)
    {
        return $query->where('groupID', self::CONSTELLATION);
    }

    public function scopeMoons($query)
    {
        return $query->where('groupID', self::MOON);
    }

    public function scopePlanets($query)
    {
        return $query->where('groupID', self::PLANET);
    }

    public function scopeSystems($query){
        return $query->where('groupID', self::SYSTEM);
    }

    public function scopeUbiquitous($query){
        return $query->whereHas('moon_content', function($sub){
            $sub->where('margetGroupID', self::UBIQUITOUS);
        });
    }

    public function scopeCommon($query){
        return $query->whereHas('moon_content', function($sub){
            $sub->where('margetGroupID', self::COMMON);
        });
    }

    public function scopeUncommon($query){
        return $query->whereHas('moon_content', function($sub){
            $sub->where('margetGroupID', self::UNCOMMON);
        });
    }

    public function scopeRare($query){
        return $query->whereHas('moon_content', function($sub){
            $sub->where('margetGroupID', self::RARE);
        });
    }

    public function scopeExceptional($query){
        return $query->whereHas('moon_content', function($sub){
            $sub->where('margetGroupID', self::EXCEPTIONAL);
        });
    }

    public function scopeStandard($query){
        return $query->whereHas('moon_content', function($sub){
            $sub->whereNotIn('margetGroupID', [self::UBIQUITOUS, self::COMMON, self::UNCOMMON, self::RARE, self::EXCEPTIONAL]);
        });
    }

    public function constellation(){
        return $this->belongsTo(MapDenormalize::class,'constellationID', 'itemID', 'constellations')
            ->withDefault([
                'itemName' => 'Unknown'
            ]);
    }

    public function moon_content()
    {
        return $this->belongsToMany(InvType::class, 'universe_moon_contents', 'moon_id', 'type_id')
            ->withPivot('rate');
    }

    public function planet()
    {
        return $this->belongsTo(MapDenormalize::class, 'orbitID', 'itemID', 'planets')
            ->withDefault([
                'itemName'=>'Unknown'
            ]);
    }

    public function region()
    {
        return $this->belongsTo(MapDenormalize::class, 'regionID', 'itemID', 'regions')
            ->withDefault([
                'itemName' => 'Unknown'
            ]);
    }

    public function system()
    {
        return $this->belongsTo(MapDenormalize::class, 'solarSystemId', 'itemID', 'systems')
            ->withDefault([
                'itemName' => 'Unknown'
            ]);
    }

    public function type()
    {
        return $this->belongsTo(InvType::class, 'typeID', 'typeID');
    }

}