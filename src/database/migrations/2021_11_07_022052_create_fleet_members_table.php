<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_members', function (Blueprint $table) {
            $table->bigInteger('fleet_id');
            $table->bigInteger('character_id');
            $table->dateTime('join_time');
            $table->string('role');
            $table->string('role_name');
            $table->integer('ship_type_id');
            $table->integer('solar_system_id');
            $table->bigInteger('squad_id');
            $table->bigInteger('wing_id');
            $table->boolean('takes_fleet_warp');

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
        Schema::dropIfExists('fleet_members');
    }
}
