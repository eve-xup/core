<?php

namespace Xup\Core\Jobs\Character;

use LaravelEveTools\EveApi\Jobs\Characters\Character as CharacterJob;
use Xup\Core\Jobs\Universe\Names;
use Xup\Core\Models\Character\Character as CharacterModel;

class Character extends CharacterJob
{

    public function handle()
    {
        $data = $this->retrieve();

        if($data->isCachedLoad() && CharacterModel::find($this->getCharacterId())) return;

        $model = CharacterModel::firstOrNew([
            'character_id' => $this->getCharacterId()
        ], [
            'name'=> $data->name,
            'corporation_id' => $data->corporation_id,
            'alliance_id'   => property_exists($data, 'alliance_id') ? $data->alliance_id : null,
            'security_status' => property_exists($data, 'security_status') ? $data->security_status : null
        ])->save();

        Names::dispatch()->delay(60);
    }
}