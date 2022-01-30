<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squads', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('fleet_id');
            $table->bigInteger('wing_id');
            $table->string('name');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('fleet_id')
                ->references('fleet_id')
                ->on('fleets')
                ->cascadeOnDelete();
            $table->foreign('wing_id')
                ->references('id')
                ->on('wings')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squads');
    }
}
