<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert some stuff
		DB::table('settings')->insert(
			array(
				'key' => 'popular_post_duration',
				'value' => '14'
			)
		);
    }
}
