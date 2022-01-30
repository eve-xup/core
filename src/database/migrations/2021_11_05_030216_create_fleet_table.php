<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->bigInteger('fleet_id')->primary();
            $table->string('title');
            $table->bigInteger('boss_id')->unsigned();
            $table->boolean('tracking')->default(true);
            $table->boolean('invite_mode')->nullable();
            $table->boolean('is_free_move')->default(false);
            $table->boolean('is_registered')->default(false);
            $table->boolean('kick_unregistered')->default(false);
            $table->longText('motd')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('fleets');
    }
}
