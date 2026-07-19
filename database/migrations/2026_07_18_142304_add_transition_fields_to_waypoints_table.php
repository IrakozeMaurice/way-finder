<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransitionFieldsToWaypointsTable extends Migration
{
    public function up()
    {
        Schema::table('waypoints', function (Blueprint $table) {

            $table->boolean('is_transition')
                ->default(false)
                ->after('floor_id');

            $table->unsignedBigInteger('linked_waypoint_id')
                ->nullable()
                ->after('is_transition');

        });
    }

    public function down()
    {
        Schema::table('waypoints', function (Blueprint $table) {

            $table->dropColumn([
                'is_transition',
                'linked_waypoint_id'
            ]);

        });
    }
}