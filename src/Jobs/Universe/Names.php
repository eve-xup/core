<?php

namespace Xup\Core\Jobs\Universe;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use LaravelEveTools\EveApi\Jobs\Universe\Names as UniverseNamesJob;
use Xup\Core\Models\Character\Character;
use Xup\Core\Models\Universe\UniverseName;

class Names extends UniverseNamesJob implements shouldBeUnique
{

    private $items_id_limit = 1000;

    public function handle()
    {
        $existing_ids = UniverseName::select('entity_id')
            ->distinct()
            ->get()
            ->pluck('entity_id');

        $entity_ids = collect();

        $entity_ids->push(Character::select('corporation_id')
            ->distinct()
            ->get()
            ->pluck('corporation_id')
            ->toArray());

        $entity_ids->push(Character::select('alliance_id')
            ->whereNotNull('alliance_id')
            ->distinct()
            ->get()
            ->pluck('alliance_id')
            ->toArray());

        $entity_ids->flatten()->diff($existing_ids)->values()->chunk($this->items_id_limit)->each(function($chunk){

            $this->request_body = collect($chunk->values()->all())->unique()->values()->all();

            $names = $this->retrieve();

            collect($names)->each(function($uname){
               UniverseName::firstOfNew([
                   'entity_id'=>$uname->id,
               ], [
                   'name'   => $uname->name,
                   'category'=>$uname->category
               ])->save();
            });
        });
    }
}