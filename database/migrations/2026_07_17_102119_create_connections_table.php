<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTable extends Migration
{
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {

            $table->id();

            $table->foreignId('floor_id');

            $table->foreignId('from_waypoint_id');

            $table->foreignId('to_waypoint_id');

            $table->integer('distance');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
}