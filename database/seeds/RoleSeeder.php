<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('roles')->insert([
    		[
    			'name' => 'actor',
    			'created_at' => now(),
    			'updated_at' => now()
    		],
    		[
    			'name' => 'producer',
    			'created_at' => now(),
    			'updated_at' => now(),
    		],
    	]);
    }
}
