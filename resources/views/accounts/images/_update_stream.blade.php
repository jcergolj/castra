<turbo-stream target="header_profile_image" action="replace">
    <template>
        <div>
            <turbo-frame id="header_profile_image">
                @include('layouts._profile-image')
            </turbo-frame>
        </div>
    </template>
</turbo-stream>

<turbo-stream target="frame_update_profile_image" action="replace" @turboReload>
    <template>
        <div>
            <turbo-frame id="frame_update_profile_image" @turboReload>
                @include('accounts._image')
            </turbo-frame>
        </div>
    </template>
</turbo-stream>
