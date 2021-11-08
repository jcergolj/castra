<?php

namespace Tests\Feature\Http\Controllers\Account;

use App\Http\Requests\Account\UpdateEmailRequest;
use App\Notifications\EmailUpdateRequestNotification;
use App\Notifications\EmailUpdateWarningNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;

/** @see \App\Http\Controllers\Account\EmailController */
class EmailControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

    public function setUp() : void
    {
        parent::setUp();

        Notification::fake();
    }

    /**
     * @test
     * @dataProvider middlewareRouteDataProvider
     */
    public function middleware_is_applied_for_routes($middleware, $route)
    {
        $this->assertContains($middleware, $this->getMiddlewareFor($route));
    }

    public function middlewareRouteDataProvider()
    {
        return [
            'Auth middleware is not applied to account.email.edit route' => ['auth', 'account.email.edit'],
            'Auth middleware is not applied to account.email.update route' => ['auth', 'account.email.update'],
            'Verified middleware is not applied to account.email.edit route' => ['verified', 'account.email.edit'],
            'Verified middleware is not applied to account.email.update route' => ['verified', 'account.email.update'],
        ];
    }

    /** @test */
    public function email_edit_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.email.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="email_update"', 'patch', route('account.email.update'))
            ->assertFormHasCSRF()
            ->assertFormHasPasswordInput('current_password')
            ->assertFormHasEmailInput('new_email')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function form_is_inside_turbo_frame()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.email.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_email"]',
                'form[action="'.route('account.email.update').'"]'
            );
    }

    /** @test */
    public function user_can_request_email_change()
    {
        Log::swap(new LogFake);

        Log::channel('security_log')->assertNotLogged('info');

        $jane = create_user(['email' => 'jane@example.com']);
        $user = create_user(['email' => 'user@example.com']);
        $jack = create_user(['email' => 'jack@example.com']);

        $response = $this->actingAs($user)
            ->from(route('account.profile'))
            ->patch(route('account.email.update'), [
                'current_password' => 'password',
                'new_email' => 'new.user.email@example.com',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('account.profile'))
            ->assertSessionHas('status');

        $this->assertSame('jane@example.com', $jane->fresh()->email);
        $this->assertSame('user@example.com', $user->fresh()->email);
        $this->assertSame('jack@example.com', $jack->fresh()->email);

        Log::channel('security_log')
            ->assertLogged('info', 1, function ($message, $context) use ($user) {
                return Str::contains($message, [$user->id, 'user@example.com', 'new.user.email@example.com']);
            });
    }

    /** @test */
    public function email_update_request_notification_is_sent_to_the_requested_email_address()
    {
        Notification::assertNothingSent(EmailUpdateRequestNotification::class);

        $user = create_user();

        $this->actingAs($user)
            ->from(route('account.profile'))
            ->patch(route('account.email.update'), [
                'current_password' => 'password',
                'new_email' => 'new.email@example.com',
            ]);

        Notification::assertSentTo($user, EmailUpdateRequestNotification::class);
    }

    /** @test */
    public function email_update_warning_notification_is_sent_to_original_email_address()
    {
        Notification::assertNothingSent(EmailUpdateWarningNotification::class);

        $user = create_user();

        $this->actingAs($user)
            ->from(route('account.profile'))
            ->patch(route('account.email.update'), [
                'current_password' => 'password',
                'new_email' => 'new.email@example.com',
            ]);

        Notification::assertSentTo($user, EmailUpdateWarningNotification::class);
    }

    /** @test */
    public function email_controller_has_email_update_request()
    {
        $this->assertRouteUsesFormRequest('account.email.update', UpdateEmailRequest::class);
    }
}
