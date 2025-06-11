<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AthleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('athletes')->insert([
        ['first_name' => 'John', 'last_name' => 'Doe', 'sport' => 'Basketball'],
        ['first_name' => 'Jane', 'last_name' => 'Smith', 'sport' => 'Soccer'],
    ]);
}
}
