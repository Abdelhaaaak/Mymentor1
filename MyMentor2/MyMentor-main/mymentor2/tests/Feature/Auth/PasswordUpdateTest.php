<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Import the route helper for clarity
use function route;

beforeEach(function () {
    // If your application requires shared setup (e.g., migrations, seeding), add it here.
    // For these tests, no additional setup is needed beyond creating a user.
});

test('password can be updated', function () {
    $user = User::factory()->create();

    // Acting as the authenticated user, navigate from the profile edit page:
    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->put(route('password.update'), [
            // By default, User::factory() sets the password to 'password'
            'current_password'      => 'password',
            'password'              => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        // After updating password, redirect back to the profile edit page:
        ->assertRedirect(route('profile.edit'));

    // Assert that the user’s password was actually updated in the database:
    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->put(route('password.update'), [
            // Supply an incorrect current password deliberately
            'current_password'      => 'wrong-password',
            'password'              => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    // Expect a validation error on the 'current_password' field within the 'updatePassword' error bag:
    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password')
        // Should redirect back to the profile edit page, not "/profile":
        ->assertRedirect(route('profile.edit'));
});
