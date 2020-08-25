<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('sexes')->insert([
    		[
    			'name' => 'male',
    			'created_at' => now(),
    			'updated_at' => now(),
    		],
    		[
    			'name' => 'female',
    			'created_at' => now(),
    			'updated_at' => now(),
    		],
    	]);
    }
}
