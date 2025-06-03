<?php

use App\Models\User;

// We import the route names in case they ever change.
use function route;

beforeEach(function () {
    // If your application requires any setup before each test (e.g., database migration),
    // you may place it here. For now, no additional setup is needed.
});

test('login screen can be rendered', function () {
    // Instead of hard‐coding '/login', we use the named route 'login'.
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email'    => $user->email,
        // By default, Laravel’s User factory sets the password to 'password'.
        'password' => 'password',
    ]);

    // Verify the user was authenticated
    $this->assertAuthenticated();
    // Upon successful login, Laravel (by default) redirects to the 'dashboard' named route.
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    // Log in the user so we can call logout
    $this->actingAs($user);

    // Use the named route 'logout' instead of '/logout'
    $response = $this->post(route('logout'));

    $this->assertGuest();
    // After logout, Laravel’s default is to redirect to '/' (home).
    $response->assertRedirect(route('home', absolute: false));
});
