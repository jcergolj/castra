<?php

namespace Tests\Feature\Http\Controllers\Account;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailUpdatedNotification;
use App\Http\Requests\Account\UpdateEmailRequest;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;

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
            ->assertViewHasForm('id="email-update"', 'patch', route('account.email.update'))
            ->assertFormHasCSRF()
            ->assertFormHasPasswordInput('current_password')
            ->assertFormHasPasswordInput('new_email')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function form_is_inside_turbo_frame()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.email.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="change_email"]',
                'form[action="'.route('account.email.update').'"]'
            );
    }

    /** @test */
    public function user_can_change_his_email()
    {
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

        $this->assertTrue('jane@example.com', $jane->fresh()->email);
        $this->assertTrue('new.user.email@example.com', $user->fresh()->email);
        $this->assertTrue('jack@example.com', $jack->fresh()->email);
    }

    /** @test */
    public function email_updated_notification_is_sent_to_the_user()
    {
        Notification::assertNothingSent(EmailUpdatedNotification::class);

        $user = create_user();

        $this->actingAs($user)
            ->from(route('account.profile'))
            ->patch(route('account.email.update'), [
                'current_password' => 'password',
                'new_email' => 'new.email@example.com',
            ]);

        Notification::assertSentTo($user, EmailUpdatedNotification::class, function () {

        });
    }

    /** @test */
    public function email_controller_has_email_update_request()
    {
        $this->assertRouteUsesFormRequest('account.email.update', UpdateEmailRequest::class);
    }
}
