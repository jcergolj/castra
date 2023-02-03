<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Events\ProfileImageUploaded;
use Illuminate\Support\Facades\Event;
use App\Listeners\ResizeImageListener;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** @see \App\Listeners\ResizeImageListener */
class ResizeImageListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assert_event_has_listener()
    {
        Event::fake()
            ->assertListening(
                ProfileImageUploaded::class,
                ResizeImageListener::class
            );
    }

    /** @test */
    public function listener_is_queued()
    {
        $this->assertInstanceOf(ShouldQueue::class, (new ResizeImageListener()));
    }

    /** @test */
    public function profile_image_is_resized()
    {
        Storage::disk('profile_image')
            ->putFileAs('', UploadedFile::fake()->image('abc123.jpg', 1000, 1000), 'abc123.jpg');

        $event = new ProfileImageUploaded($user = create_user(['profile_image' => 'abc123.jpg']));
        $listener = new ResizeImageListener();
        $listener->handle($event);

        $imageProperties = getimagesize(config('filesystems.disks.profile_image.root')."/{$user->profile_image}");

        $this->assertSame(200, $imageProperties[0]);
        $this->assertSame(200, $imageProperties[1]);

        Storage::disk('profile_image')->delete("{$user->profile_image}");
    }
}
