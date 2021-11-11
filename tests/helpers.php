<?php

use App\Models\User;

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

/**
 * Member seeder.
 *
 * @param  $attributes
 * @return \App\Models\User
 */
function create_member($attributes = []) : User
{
    return create_user($attributes);
}

/**
 * Admin seeder.
 *
 * @param  $attributes
 * @return \App\Models\User
 */
function create_admin($attributes = []) : User
{
    return User::factory()->admin()->create($attributes);
}
