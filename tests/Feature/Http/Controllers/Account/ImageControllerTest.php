<?php

namespace Tests\Feature\Http\Controllers\Account;

use App\Events\ProfileImageUploaded;
use App\Http\Requests\Account\UpdateImageRequest;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;
use Tonysm\TurboLaravel\Testing\InteractsWithTurbo;

/** @see \App\Http\Controllers\Account\ImageController */
class ImageControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware, InteractsWithTurbo;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
        Storage::fake('profile_image');

        $this->imageFile = UploadedFile::fake()
            ->image('image.jpg', 1000, 1000);
    }

    /**
     * @test
     *
     * @dataProvider middlewareRouteDataProvider
     */
    public function middleware_is_applied_for_routes($middleware, $route)
    {
        $this->assertContains($middleware, $this->getMiddlewareFor($route));
    }

    public function middlewareRouteDataProvider()
    {
        return [
            'Auth middleware is not applied to accounts.profile-images.edit route' => ['auth', 'accounts.profile-images.edit'],
            'Auth middleware is not applied to accounts.profile-images.update route' => ['auth', 'accounts.profile-images.update'],
            'Verified middleware is not applied to accounts.profile-images.edit route' => ['verified', 'accounts.profile-images.edit'],
            'Verified middleware is not applied to accounts.profile-images.update route' => ['verified', 'accounts.profile-images.update'],
        ];
    }

    /** @test */
    public function profile_image_edit_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.profile-images.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="update_profile_image"', 'patch', route('accounts.profile-images.update'))
            ->assertFormHasCSRF()
            ->assertFormHasFileInput('profile_image')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function form_is_inside_turbo_frame()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.profile-images.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_profile_image"]',
                'form[action="'.route('accounts.profile-images.update').'"]'
            );
    }

    /** @test */
    public function user_can_update_profile_image()
    {
        $jane = create_user(['profile_image' => 'jane.png']);
        $user = create_user(['profile_image' => null]);
        $jack = create_user(['profile_image' => 'jack.png']);

        $response = $this->actingAs($user)
            ->from(route('accounts.profile-images.edit'))
            ->patch(route('accounts.profile-images.update'), [
                'profile_image' => $this->imageFile,
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('accounts.profile'))
            ->assertSessionHas('status')
            ->assertNotTurboStream();

        $this->assertNotNull($user->fresh()->profile_image);
        Storage::disk('profile_image')->assertExists($user->profile_image);

        $this->assertSame('jane.png', $jane->fresh()->profile_image);
        $this->assertSame('jack.png', $jack->fresh()->profile_image);
    }

    /** @test */
    public function updating_image_from_turbo_request_returns_turbo_stream_response()
    {
        $response = $this->actingAs(create_user())
            ->turbo()
            ->patch(route('accounts.profile-images.update'), [
                'profile_image' => $this->imageFile,
            ]);

        $response->assertTurboStream();
    }

    /** @test */
    public function event_profile_image_uploaded_is_dispatched()
    {
        Event::assertNotDispatched(ProfileImageUploaded::class);

        $user = create_user(['profile_image' => null]);

        $this->actingAs($user)
            ->from(route('accounts.profile-images.edit'))
            ->patch(route('accounts.profile-images.update'), [
                'profile_image' => $this->imageFile,
            ]);

        Event::assertDispatched(ProfileImageUploaded::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function old_user_image_is_deleted()
    {
        $user = create_user(['profile_image' => 'abc123.jpg']);

        Storage::disk('profile_image')
            ->putFileAs('', UploadedFile::fake()->image('abc123.jpg', 1000, 1000), 'abc123.jpg');

        $this->actingAs($user)
            ->from(route('accounts.profile-images.edit'))
            ->patch(route('accounts.profile-images.update'), [
                'profile_image' => $this->imageFile,
            ]);

        Storage::disk('profile_image')->assertExists($user->fresh()->profile_image);

        Storage::disk('profile_image')->assertMissing('abc123.jpg');
    }

    /** @test */
    public function email_controller_has_email_update_request()
    {
        $this->assertRouteUsesFormRequest('accounts.profile-images.update', UpdateImageRequest::class);
    }
}
