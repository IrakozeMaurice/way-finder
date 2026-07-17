<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallwaysTable extends Migration
{
    public function up()
    {
        Schema::create('hallways', function (Blueprint $table) {

            $table->id();

            $table->foreignId('floor_id');

            $table->integer('x1');

            $table->integer('y1');

            $table->integer('x2');

            $table->integer('y2');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('hallways');
    }
}