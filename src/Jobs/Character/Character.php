<?php

namespace Xup\Core\Jobs\Character;

use LaravelEveTools\EveApi\Jobs\Characters\Character as CharacterJob;
use Xup\Core\Jobs\Universe\Names;
use Xup\Core\Models\Character\Character as CharacterModel;

class Character extends CharacterJob
{

    const UNIVERSE_NAME_DELAY = 10;

    public $tags = ['default'];

    public $queue = 'default';

    public function handle()
    {
        $data = $this->retrieve();

        if($data->isCachedLoad() && CharacterModel::find($this->getCharacterId())) return;

        $model = CharacterModel::firstOrNew([
            'character_id' => $this->getCharacterId()
        ]);

        $model->corporation_id = $data->corporation_id;
        if(property_exists($data, 'alliance_id'))
            $model->alliance_id = $data->alliance_id;

        if(property_exists($data, 'security_status'))
            $model->security_status = $data->security_status;

        $model->save();

        Names::dispatch()->delay(self::UNIVERSE_NAME_DELAY);
    }
}