<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Import the route helper for clarity
use function route;

/*
|--------------------------------------------------------------------------
| Constants for Test Reuse
|--------------------------------------------------------------------------
|
| We define TEST_USER_NAME once so that if we ever change the test user’s
| name across multiple tests, we only update it here. Likewise, by using
| named routes below (profile.edit, profile.update, profile.destroy), we
| eliminate all hard‐coded "/profile" literals.
|
| @see https://www.php.net/manual/en/function.define.php
| :contentReference[oaicite:0]{index=0}
| @see https://laravel.com/docs/10.x/routing#named-routes
| :contentReference[oaicite:1]{index=1}
*/

if (! defined('TEST_USER_NAME')) {
    define('TEST_USER_NAME', 'Test User');
}

beforeEach(function () {
    // (Optional) Additional setup—e.g., migrations—can go here.
});

/**
 * Test that the profile page (edit form) can be displayed.
 */
test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        // Use the named route 'profile.edit' (GET /profile)
        ->get(route('profile.edit'));

    $response->assertOk();
});

/**
 * Test that profile information (name + email) can be updated.
 */
test('profile information can be updated', function () {
    $user = User::factory()->create();

    $newEmail = 'test@example.com';

    $response = $this
        ->actingAs($user)
        // Submit to the named route 'profile.update' (PATCH /profile)
        ->patch(route('profile.update'), [
            'name'  => TEST_USER_NAME,
            'email' => $newEmail,
        ]);

    $response
        ->assertSessionHasNoErrors()
        // After successful update, redirect back to the edit page
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    // Assert that the name and email were updated correctly
    $this->assertSame(TEST_USER_NAME, $user->name);
    $this->assertSame($newEmail, $user->email);
    // Since the email changed, email_verified_at should be null
    $this->assertNull($user->email_verified_at);
});

/**
 * Test that if the email remains unchanged, the email-verified status is preserved.
 */
test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        // PATCH to the same email—even if we change the name
        ->patch(route('profile.update'), [
            'name'  => TEST_USER_NAME,
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    // Since we did not change the email, email_verified_at should remain non-null
    $this->assertNotNull($user->refresh()->email_verified_at);
});

/**
 * Test that a user can delete their own account (provided the correct password).
 */
test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        // Use named route 'profile.destroy' (DELETE /profile)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        // After deletion, Laravel redirects to the home page ("/")
        ->assertRedirect(route('home'));

    // The user should no longer exist in the database, and the session should be logged out
    $this->assertGuest();
    $this->assertNull($user->fresh());
});

/**
 * Test that providing an incorrect current password prevents account deletion.
 */
test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        // Navigate from the edit page to ensure proper redirection on error
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        // Expect a validation error in the 'userDeletion' error bag for the 'password' field
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        // Redirect back to the profile edit page
        ->assertRedirect(route('profile.edit'));

    // User should still exist because deletion was not authorized
    $this->assertNotNull($user->fresh());
});
