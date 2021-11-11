<?php

namespace App\Http\Controllers\Account;

use App\Events\ProfileImageUploaded;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateImageRequest;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /** @return \Illuminate\View\View */
    public function edit()
    {
        return view('account.image.edit');
    }

    /**
     * @param  \App\Http\Requests\Account\UpdateImageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateImageRequest $request)
    {
        if ($request->user()->profile_image !== null) {
            Storage::disk('profile_image')
                ->delete("{$request->user()->profile_image}");
        }

        /** @var \Illuminate\Http\UploadedFile */
        $profileImage = $request->file('profile_image');
        $path = $profileImage->store('/', 'profile_image');

        $request->user()->saveImage($path);

        ProfileImageUploaded::dispatch($request->user());

        msg_success('Your profile\'s image has been successfully updated.', 'update-image');

        if ($request->wantsTurboStream()) {
            return response()->turboStreamView('account.image._update_stream');
        }

        return redirect()->route('account.profile');
    }
}
