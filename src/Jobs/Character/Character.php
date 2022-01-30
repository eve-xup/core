<?php

namespace Xup\Core\Jobs\Character;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use LaravelEveTools\EveApi\Jobs\Characters\Character as CharacterJob;
use Xup\Core\Jobs\Universe\Names;
use Xup\Core\Models\Character\Character as CharacterModel;

class Character extends CharacterJob
{
    use SerializesModels;

    const UNIVERSE_NAME_DELAY = 10;

    public $queue = 'character';

    public bool $force;

    public function __construct(int $character_id, $force = false)
    {
        Log::debug(sprintf('setting up job %s for %d', static::class, $character_id));
        parent::__construct($character_id);
        $this->force = $force;
    }



    public function handle()
    {


        $data = $this->retrieve();

        if ($this->skipIfTrue($data)) return;

        $model = CharacterModel::firstOrNew([
            'character_id' => $this->getCharacterId()
        ], [
            'name' => $data->name
        ]);


        $model->corporation_id = $data->corporation_id;
        if (property_exists($data, 'alliance_id'))
            $model->alliance_id = $data->alliance_id;

        if (property_exists($data, 'security_status'))
            $model->security_status = $data->security_status;

        $model->save();


        Names::dispatch()->delay(self::UNIVERSE_NAME_DELAY);
    }
}
