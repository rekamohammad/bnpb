<?php

namespace Botble\Base\Seeds;

use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionFlagsTableSeeder::class);
    }
}
