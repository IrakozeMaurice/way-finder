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

            $table->foreignId('from_waypoint');

            $table->foreignId('to_waypoint');

            $table->integer('distance');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
}