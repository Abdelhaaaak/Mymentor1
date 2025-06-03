<?php

use App\Models\User;

// Import the route helper function for clarity
use function route;

beforeEach(function () {
    // If your application requires any shared setup (e.g., seeding),
    // place it here. For now, no extra setup is needed.
});

test('confirm password screen can be rendered', function () {
    // Create a user and act as that authenticated user
    $user = User::factory()->create();

    // Instead of hard‐coding '/confirm-password', use the named route 'password.confirm'
    $response = $this->actingAs($user)
                     ->get(route('password.confirm'));

    // Assert the page loads successfully (HTTP 200)
    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    // Simulate the user logging in and submitting the correct password
    $response = $this->actingAs($user)
                     ->post(route('password.confirm'), [
                         'password' => 'password',
                     ]);

    // On successful confirmation, Laravel redirects back (usually to intended URL).
    $response->assertRedirect();
    // Ensure no validation errors occurred
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    // Act as the user and submit a wrong password
    $response = $this->actingAs($user)
                     ->post(route('password.confirm'), [
                         'password' => 'wrong-password',
                     ]);

    // The form should return validation errors for an incorrect password
    $response->assertSessionHasErrors();
});
