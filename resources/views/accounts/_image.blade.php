<turbo-frame id="frame_update_profile_image">
    <div class="grid grid-cols-6 gap-6 mt-4">
        <div class="text-gray-700 col-span-5">
            <img
                class="rounded-full h-24 w-24"
                src="{{ user()->profileImageFile }}"
            />
        </div>
        <a href="{{ route('accounts.profile-images.edit') }}">
            <x-svg.edit title="update profile image" />
        </a>
    </div>
    <div class="grid grid-cols-1 mt-4">
        <x-status.inline messageBag="update-image" />
    </div>
</turbo-frame>
