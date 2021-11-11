<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** @return void */
    public function run()
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
