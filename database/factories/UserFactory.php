<?php

namespace Database\Factories;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /** @return array */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'profile_image' => null,
            'role' => UserRoles::member->name,
        ];
    }

    /** @return \Illuminate\Database\Eloquent\Factories\Factory */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /** @return \Illuminate\Database\Eloquent\Factories\Factory */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => UserRoles::admin->name,
            ];
        });
    }
}
