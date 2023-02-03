<?php

namespace Tests\Unit\Models;

use App\Enums\UserRoles;
use App\Models\Concerns\LogsDeleteActivity;
use App\Models\User;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Tests\TestCase;

/** @see \App\Models\User */
class UserTest extends TestCase
{
    /** @test */
    public function assert_model_uses_logs_delete_activity_trait()
    {
        $this->assertContains(LogsDeleteActivity::class, class_uses(User::class));
    }

    /** @test */
    public function assert_model_uses_has_factory_trait()
    {
        $this->assertContains(HasFactory::class, class_uses(User::class));
    }

    /** @test */
    public function assert_model_uses_notifiable_trait()
    {
        $this->assertContains(Notifiable::class, class_uses(User::class));
    }

    /** @test */
    public function assert_model_uses_soft_deletes_trait()
    {
        $this->assertContains(SoftDeletes::class, class_uses(User::class));
    }

    /** @test */
    public function assert_model_has_restore_route_name()
    {
        $this->assertSame('admin.users.restore', create_user()->restoreRouteName());
    }

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
    public function assert_deleted_at_is_cast()
    {
        $this->assertSame('datetime', create_user()->getCasts()['deleted_at']);
    }

    /** @test */
    public function assert_role_is_cast()
    {
        $this->assertNotInstanceOf(UserRoles::class, create_user()->getCasts()['role']);
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
    public function new_eloquent_builder()
    {
        $query = $this->app->make(Builder::class);
        $this->assertInstanceOf(UserQueryBuilder::class, create_user()->newEloquentBuilder($query));
    }

    /** @test */
    public function is_admin()
    {
        $admin = create_admin();
        $member = create_member();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($member->isAdmin());
    }

    /** @test */
    public function is_it_me()
    {
        $joe = create_member(['email' => 'joe@example.com']);
        $jane = create_member(['email' => 'jane@example.com']);

        $this->actingAs($joe);
        $this->assertTrue($joe->isItMe());

        $this->assertFalse($jane->isItMe());
    }
}
