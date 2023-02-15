<?php

use App\Models\User;

/**
 * User seeder.
 */
function create_user($attributes = []): User
{
    return User::factory()->create($attributes);
}

/**
 * User seeder.
 */
function make_user($attributes = []): User
{
    return User::factory()->make($attributes);
}

/**
 * Member seeder.
 */
function create_member($attributes = []): User
{
    return create_user($attributes);
}

/**
 * Admin seeder.
 */
function create_admin($attributes = []): User
{
    return User::factory()->admin()->create($attributes);
}
