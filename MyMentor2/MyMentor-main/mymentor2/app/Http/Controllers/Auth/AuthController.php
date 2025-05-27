<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Registration function to show registration form
    public function create()
    {
        return view('auth.register');
    }

    // Handle user registration and log the user in
    public function store(Request $request)
    {
        // Validate the incoming request data
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        // Create the user in the database
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // Automatically log the user in after registration
        Auth::login($user);

        // Redirect the user to their profile or dashboard
        return redirect()->route('profile.show');  // Or dashboard route if preferred
    }

    // Show login form
    public function loginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Log in the user if credentials are correct
        Auth::login($user);

        // Redirect the user to the profile or dashboard
        return redirect()->route('profile.show');  // Or dashboard route
    }

    // Logout function
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out
        return redirect()->route('home');  // Redirect to the home page after logout
    }
    
}
