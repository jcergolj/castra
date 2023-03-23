<?php

namespace App\Http\Controllers\Account;

use App\Events\ProfileImageUploaded;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateImageRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ImageController extends Controller
{
    public function edit(): View
    {
        return view('accounts.images.edit');
    }

    public function update(UpdateImageRequest $request): ResponseFactory|Response|RedirectResponse
    {
        if ($request->user()->profile_image !== null) {
            Storage::disk('profile_image')
                ->delete("{$request->user()->profile_image}");
        }

        /** @var \Illuminate\Http\UploadedFile $profileImage */
        $profileImage = $request->file('profile_image');
        $path = $profileImage->store('/', 'profile_image');

        $request->user()->saveImage($path);

        ProfileImageUploaded::dispatch($request->user());

        msg_success('Your profile\'s image has been successfully updated.', 'update-image');

        if ($request->wantsTurboStream()) {
            return response()->turboStreamView('accounts.images._update_stream');
        }

        return to_route('accounts.profile');
    }
}
