<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

/** @see \App\Models\User */
class UserTest extends TestCase
{
    /** @test */
    public function assert_id_is_cast()
    {
        $this->assertSame('integer', create_user()->getCasts()['id']);
    }

    /** @test */
    public function assert_email_verified_at_is_cast()
    {
        $this->assertSame('datetime', create_user()->getCasts()['email_verified_at']);
    }

    /** @test */
    public function get_image_file_attribute()
    {
        $user = create_user(['profile_image' => null]);

        $this->assertSame(url('images/default-user.png'), $user->profile_image_file);

        $user = create_user(['profile_image' => 'image.png']);

        $this->assertSame(
            config('filesystems.disks.profile_image.url')."/{$user->profile_image}",
            $user->profile_image_file
        );
    }

    /** @test */
    public function save_profile_image()
    {
        $user = create_user(['profile_image' => null]);

        $user->saveImage('new-image-name.jpg');

        $this->assertSame('new-image-name.jpg', $user->fresh()->profile_image);
    }

    /** @test */
    public function is_admin()
    {
        $admin = create_admin();
        $member = create_member();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($member->isAdmin());
    }
}
