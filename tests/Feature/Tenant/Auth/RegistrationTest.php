<?php

namespace Tests\Feature\Tenant\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TenantTestCase;
use Tests\Traits\HasTenantContext;

class RegistrationTest extends TenantTestCase
{
    use RefreshDatabase, HasTenantContext;

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->withTenantContext(function () {
            $response = $this->get('/register');

            $response->assertStatus(200);
        });
    }

    public function test_new_users_can_register(): void
    {
        $this->withTenantContext(function () {
            $response = $this->post('/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $this->assertAuthenticated();
            $response->assertRedirect(route('dashboard', absolute: false));
        });
    }
}
