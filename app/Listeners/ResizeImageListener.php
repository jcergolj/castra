<?php

namespace App\Listeners;

use App\Events\ProfileImageUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ResizeImageListener implements ShouldQueue
{
    use InteractsWithQueue;

    /** @var object */
    public $event;

    /**
     * @return void
     */
    public function handle(ProfileImageUploaded $event)
    {
        $file = Storage::disk('profile_image')->get("{$event->user->profile_image}");

        Image::make($file)
            ->resize(200, 200)
            ->save(config('filesystems.disks.profile_image.root')."/{$event->user->profile_image}");
    }
}
