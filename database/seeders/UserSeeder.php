<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** @return void */
    public function run()
    {
        if (app()->isProduction()) {
            return;
        }

        User::factory()
            ->create([
                'email' => 'me@jcergolj.me.uk',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' =>  null,
            ]);

        User::factory()
            ->create([
                'email' => 'admin@jcergolj.me.uk',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' =>  null,
                'role' =>  User::ADMIN,
            ]);

            $faker = \Faker\Factory::create();

            User::factory(20)
                ->create([
                    'email' =>  $faker->unique()->email(),
                    'email_verified_at' => now(),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                    'remember_token' =>  null,
                    'role' =>  User::MEMBER,
                ]);
    }
}
