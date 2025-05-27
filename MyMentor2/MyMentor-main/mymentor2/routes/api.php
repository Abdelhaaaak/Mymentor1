<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FeedbackController;

/**
 * 🔹 Test Route
 */
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

/**
 * 🔹 Register Route
 */
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'User registered successfully.',
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
});

/**
 * 🔹 Login Route
 */
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Invalid login credentials.'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful.',
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
});

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json($request->user());
});


/**
 * 🔐 Protected Routes (Require Token)
 */
Route::middleware('auth:sanctum')->group(function () {

    // ✅ Profile (connected user)
    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });

    // 🔹 Profiles
    Route::get('/profiles', [ProfileController::class, 'index']);
    Route::get('/profiles/{id}', [ProfileController::class, 'show']);
    Route::put('/profiles/{id}', [ProfileController::class, 'update']);

    // 🔹 Skills
    Route::get('/skills', [SkillController::class, 'index']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);

    // 🔹 Sessions
    Route::get('/sessions', [SessionController::class, 'index']);
    Route::post('/sessions', [SessionController::class, 'store']);

    // 🔹 Messages
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/messages/{senderId}/{receiverId}', [MessageController::class, 'conversation']);

    // 🔹 Feedback
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/feedback/session/{sessionId}', [FeedbackController::class, 'sessionFeedback']);

    // 🔹 Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    });
});
