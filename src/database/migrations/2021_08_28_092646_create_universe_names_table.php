<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniverseNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('universe_names', function (Blueprint $table) {
            $table->bigInteger('entity_id')->primary();
            $table->string('name');
            $table->enum('category',[
                'alliance',
                'character',
                'constellations',
                'corporations',
                'inventory_type',
                'region',
                'solar_system',
                'stations'
            ]);
            $table->index('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('universe_names');
    }
}
