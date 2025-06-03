<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

// Import the route helper for clarity
use function route;

beforeEach(function () {
    // If additional shared setup is needed, add it here.
    // For instance, migrating the database or seeding roles.
});

test('reset password link screen can be rendered', function () {
    // Use named route 'password.request' (GET /forgot-password)
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Submit to named route 'password.email' (POST /forgot-password)
    $this->post(route('password.email'), ['email' => $user->email]);

    // Assert that a ResetPassword notification was sent
    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Trigger sending the reset link
    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        // Use named route 'password.reset' (GET /reset-password/{token})
        $response = $this->get(route('password.reset', ['token' => $notification->token]));

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Send the reset link
    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        // Use named route 'password.update' (POST /reset-password)
        $response = $this->post(route('password.update'), [
            'token'                 => $notification->token,
            'email'                 => $user->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            // After successful reset, Laravel typically redirects to 'login'
            ->assertRedirect(route('login'));

        return true;
    });
});
