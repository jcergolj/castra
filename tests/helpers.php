<?php

use App\Models\User;
use Database\Factories\UserFactory;

/**
 * User seeder.
 *
 * @param  $attributes
 * @return \App\Models\User
 */
function create_user($attributes = []) : User
{
    return User::factory()->create($attributes);
}

/**
 * User seeder.
 *
 * @param  $attributes
 * @return \App\Models\User
 */
function make_user($attributes = []) : User
{
    return User::factory()->make($attributes);
}
