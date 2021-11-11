<?php

namespace Tests\Feature\Http\Controllers\Account;

use App\Http\Requests\Account\UpdateEmailRequest;
use App\Notifications\EmailUpdateRequestNotification;
use App\Notifications\EmailUpdateWarningNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

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
            'Auth middleware is not applied to accounts.emails.edit route' => ['auth', 'accounts.emails.edit'],
            'Auth middleware is not applied to accounts.emails.update route' => ['auth', 'accounts.emails.update'],
            'Verified middleware is not applied to accounts.emails.edit route' => ['verified', 'accounts.emails.edit'],
            'Verified middleware is not applied to accounts.emails.update route' => ['verified', 'accounts.emails.update'],
        ];
    }

    /** @test */
    public function email_edit_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.emails.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="email_update"', 'patch', route('accounts.emails.update'))
            ->assertFormHasCSRF()
            ->assertFormHasPasswordInput('current_password')
            ->assertFormHasEmailInput('new_email')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function form_is_inside_turbo_frame()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.emails.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_email"]',
                'form[action="'.route('accounts.emails.update').'"]'
            );
    }

    /** @test */
    public function email_update_request_notification_is_sent_to_the_requested_email_address()
    {
        Notification::assertNothingSent(EmailUpdateRequestNotification::class);

        $user = create_user();

        $this->actingAs($user)
            ->from(route('accounts.profile'))
            ->patch(route('accounts.emails.update'), [
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
            ->from(route('accounts.profile'))
            ->patch(route('accounts.emails.update'), [
                'current_password' => 'password',
                'new_email' => 'new.email@example.com',
            ]);

        Notification::assertSentTo($user, EmailUpdateWarningNotification::class);
    }

    /** @test */
    public function email_controller_has_email_update_request()
    {
        $this->assertRouteUsesFormRequest('accounts.emails.update', UpdateEmailRequest::class);
    }
}
