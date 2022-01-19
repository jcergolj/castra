<?php

namespace Tests\Unit\QueryBuilders;

use App\Enums\UserRoles;
use App\Models\User;
use Tests\TestCase;

/** @see \App\QueryBuilders\UserQueryBuilder */
/**
 *  I have a dilemma here. Arguably this test in a feature one not unit one. We could test it in isolation but the value of such test would be negligible.
 *  Testing UserQueryBuilder from the prospective of user model makes more sense and bring much more value.
 */
class UserQueryBuilderTest extends TestCase
{
    /** @test */
    public function filter_by_email()
    {
        $joe = create_user(['email' => 'joe@example.com']);
        $jack = create_user(['email' => 'jack@example.com']);
        $users = User::search('joe@example.com')->get();
        $this->assertCount(1, $users);
        $this->assertTrue($users->contains($joe));
        $this->assertFalse($users->contains($jack));
    }

    /** @test */
    public function filter_by_role()
    {
        $member = create_user(['email' => 'member@example.com']);
        $admin = create_admin(['email' => 'admin@example.com']);

        $users = User::role(UserRoles::admin->value)->get();

        $this->assertCount(1, $users);
        $this->assertTrue($users->contains($admin));
        $this->assertFalse($users->contains($member));
    }
}
