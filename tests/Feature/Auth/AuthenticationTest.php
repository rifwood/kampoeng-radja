<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_active_user_can_authenticate_using_username_and_six_digit_pin(): void
    {
        $user = User::factory()->create(['pin' => '654321']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'pin' => '654321',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_pin_is_stored_as_a_hash(): void
    {
        $user = User::factory()->create(['pin' => '654321']);

        $this->assertNotSame('654321', $user->getRawOriginal('pin'));
        $this->assertTrue(Hash::check('654321', $user->getRawOriginal('pin')));
    }

    public function test_login_requires_exactly_six_numeric_pin_digits(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['username' => $user->username, 'pin' => '12345'])
            ->assertSessionHasErrors('pin');
        $this->post('/login', ['username' => $user->username, 'pin' => '12345a'])
            ->assertSessionHasErrors('pin');

        $this->assertGuest();
    }

    public function test_user_cannot_authenticate_with_invalid_pin(): void
    {
        $user = User::factory()->create(['pin' => '654321']);

        $this->post('/login', [
            'username' => $user->username,
            'pin' => '111111',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_authenticate(): void
    {
        $user = User::factory()->inactive()->create(['pin' => '654321']);

        $this->post('/login', [
            'username' => $user->username,
            'pin' => '654321',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
