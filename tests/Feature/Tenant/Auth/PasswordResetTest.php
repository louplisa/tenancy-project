<?php

namespace Tests\Feature\Tenant\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TenantTestCase;
use Tests\Traits\HasTenantContext;

class PasswordResetTest extends TenantTestCase
{
    use RefreshDatabase, HasTenantContext;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $this->withTenantContext(function () {
            $response = $this->get('/forgot-password');

            $response->assertStatus(200);
        });
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $this->withTenantContext(function () {
            Notification::fake();

            /** @var User $user */
            $user = User::factory()->create();

            $this->post('/forgot-password', ['email' => $user->email]);

            Notification::assertSentTo($user, ResetPassword::class);
        });
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $this->withTenantContext(function () {
            Notification::fake();

            /** @var User $user */
            $user = User::factory()->create();

            $this->post('/forgot-password', ['email' => $user->email]);

            Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
                $response = $this->get('/reset-password/'.$notification->token);

                $response->assertStatus(200);

                return true;
            });
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $this->withTenantContext(function () {
            Notification::fake();

            /** @var User $user */
            $user = User::factory()->create();

            $this->post('/forgot-password', ['email' => $user->email]);

            Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
                $response = $this->post('/reset-password', [
                    'token' => $notification->token,
                    'email' => $user->email,
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ]);

                $response
                    ->assertSessionHasNoErrors()
                    ->assertRedirect(route('login'));

                return true;
            });
        });
    }
}
