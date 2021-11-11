<?php

namespace Tests\Unit\Listeners;

use App\Events\ProfileImageUploaded;
use App\Listeners\ResizeImageListener;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** @see \App\Listeners\ResizeImageListener */
class ResizeImageListenerTest extends TestCase
{
    use RefreshDatabase;

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
