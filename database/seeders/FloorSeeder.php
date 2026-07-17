<?php

namespace Database\Seeders;

use App\Models\Floor;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    public function run()
    {
        Floor::truncate();

        Floor::insert([

            ['name'=>'Ground Floor'],

            ['name'=>'First Floor'],

            ['name'=>'Second Floor'],

            ['name'=>'Third Floor']

        ]);
    }
}