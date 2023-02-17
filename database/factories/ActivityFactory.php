<?php

namespace Database\Factories;

use App\Enums\ActivityEvents;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $user = create_user();

        return [
            'log_name' => 'default',
            'description' => 'here is a description',
            'subject_type' => $user::class,
            'event' => ActivityEvents::Deleted,
            'subject_id' => $user->id,
            'causer_type' => $user::class,
            'causer_id' => $user->id,
            'properties' => ['subject' => ['email' => 'joe.doe@example.com']],
            'batch_uuid' => null,
        ];
    }
}
