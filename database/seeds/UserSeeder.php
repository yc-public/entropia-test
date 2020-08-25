<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$roles = DB::table('roles')->pluck('id');
    	$sexes = DB::table('sexes')->pluck('id');
    	$faker = \Faker\Factory::create();

    	foreach (range(1,20) as $item) {
    		DB::table('users')->insert([
    			'role_id' => $faker->randomElement($roles),
    			'sex_id' => $faker->randomElement($sexes),
    			'name' => $faker->firstName . ' ' . $faker->lastName,
    			'dob' => $faker->date,
    			'bio' => $faker->text,
    			'created_at' => now(),
    			'updated_at' => now()
    		]);
    	}  
    }
}
