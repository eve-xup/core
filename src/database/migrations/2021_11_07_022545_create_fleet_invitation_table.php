<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fleet_id');
            $table->bigInteger('character_id');
            $table->timestamps();

            $table->foreign('fleet_id')
                ->references('fleet_id')
                ->on('fleets')
                ->cascadeOnDelete();
            $table->foreign('character_id')->references('character_id')->on('characters');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_invitations');
    }
}
