<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /** @return array */
    public function definition()
    {
        $user = create_user();

        return [
            'log_name' => 'default',
            'description' => 'here is a description',
            'subject_type' => $user::class,
            'event' => null,
            'subject_id' => $user->id,
            'causer_type' => $user::class,
            'causer_id' => $user->id,
            'properties' => [],
            'batch_uuid' => null,
        ];
    }
}
