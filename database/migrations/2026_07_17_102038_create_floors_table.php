<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorsTable extends Migration
{
    public function up()
    {
        Schema::create('floors', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('floor_plan')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('floors');
    }
}