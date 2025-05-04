<?php

namespace Tests\Feature\Tenant\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TenantTestCase;
use Tests\Traits\HasTenantContext;

class PasswordConfirmationTest extends TenantTestCase
{
    use RefreshDatabase, HasTenantContext;

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $this->withTenantContext(function () {
            /** @var User $user */
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/confirm-password');

            $response->assertStatus(200);
        });
    }

    public function test_password_can_be_confirmed(): void
    {
        $this->withTenantContext(function () {
            /** @var User $user */
            $user = User::factory()->create();

            $response = $this->actingAs($user)->post('/confirm-password', [
                'password' => 'password',
            ]);

            $response->assertRedirect();
            $response->assertSessionHasNoErrors();
        });
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $this->withTenantContext(function () {
            /** @var User $user */
            $user = User::factory()->create();

            $response = $this->actingAs($user)->post('/confirm-password', [
                'password' => 'wrong-password',
            ]);

            $response->assertSessionHasErrors();
        });
    }
}
