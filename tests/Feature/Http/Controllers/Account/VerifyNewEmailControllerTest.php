<?php

namespace Tests\Feature\Http\Controllers\Account;

use App\Services\SignedUrlGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\VerifyNewEmailController */
class VerifyNewEmailControllerTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     */
    public function throttle_is_applied_for_account_verification_verify_route()
    {
        $this->assertContains('throttle', $this->getMiddlewareFor('account.verification.verify'));
    }

    /** @test */
    public function abort_if_request_has_invalid_signature()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);

        $this->get('invalid');
    }

    /** @test */
    public function abort_if_user_does_not_exists()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail(make_user(), 'new.email@example.com', Carbon::now()->addDay());

        $this->get($url);
    }

    /** @test */
    public function new_email_is_verified()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail($user, 'new.email@example.com', Carbon::now()->addDay());

        $response = $this->get($url);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('status')
            ->assertRedirect(route('login'));

        $this->assertSame('new.email@example.com', $user->fresh()->email);
    }

    /** @test */
    public function confirm_user_email_if_authenticated()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail($user, 'new.email@example.com', Carbon::now()->addDay());
        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('status')
            ->assertRedirect(route('login'));

        $this->assertSame('new.email@example.com', $user->fresh()->email);
    }
}
