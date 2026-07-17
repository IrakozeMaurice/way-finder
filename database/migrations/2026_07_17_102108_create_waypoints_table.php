<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaypointsTable extends Migration
{
    public function up()
    {
        Schema::create('waypoints', function (Blueprint $table) {

            $table->id();

            $table->foreignId('floor_id');

            $table->integer('x');

            $table->integer('y');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('waypoints');
    }
}