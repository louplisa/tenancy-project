<?php

namespace Tests\Feature\Tenant\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TenantTestCase;
use Tests\Traits\HasTenantContext;

class AuthenticationTest extends TenantTestCase
{
    use RefreshDatabase, HasTenantContext;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->withTenantContext(function () {
            $response = $this->get('/login');

            $response->assertStatus(200);
        });
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $this->withTenantContext(function () {
            /** @var User $user */
            $user = User::factory()->create();

            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);
            $this->assertAuthenticated();
            $response->assertRedirect(route('dashboard', absolute: false));
        });
        }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $this->withTenantContext(function () {
            /** @var User $user */
            $user = User::factory()->create();

            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
            $this->assertGuest();
        });
    }

    public function test_users_can_logout(): void
    {
        $this->withTenantContext(function () {
            /** @var User $user */
            $user = User::factory()->create();

            $response = $this->actingAs($user)->post('/logout');

            $this->assertGuest();
            $response->assertRedirect('/');
        });
    }
}
