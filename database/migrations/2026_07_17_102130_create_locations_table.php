<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('floor_id');

            $table->string('name');

            $table->integer('x')->nullable();

            $table->integer('y')->nullable();

            $table->foreignId('waypoint_id')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
}