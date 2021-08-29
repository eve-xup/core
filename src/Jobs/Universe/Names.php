<?php

namespace Xup\Core\Jobs\Universe;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use LaravelEveTools\EveApi\Jobs\Universe\Names as UniverseNamesJob;
use Xup\Core\Models\Universe\UniverseName;

class Names extends UniverseNamesJob implements shouldBeUnique
{

    private $items_id_limit = 1000;

    private $existing_ids;

    public function handle()
    {
        $this->existing_ids = UniverseName::select('entity_id')
            ->distinct()
            ->get()
            ->pluck('entity_id');

        $this->existing_ids->values()->chunk($this->items_id_limit)->each(function($chunk){

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