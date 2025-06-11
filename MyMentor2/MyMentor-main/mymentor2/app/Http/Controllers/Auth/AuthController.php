<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Common validation rule for required string fields.
     */
    protected const BASIC_RULE = 'required|string';

    /**
     * Show the general registration form.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle general registration.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'     => self::BASIC_RULE,
            'email'    => self::BASIC_RULE . '|unique:users,email',
            'password' => self::BASIC_RULE . '|confirmed',
        ]);

        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('profile.show.self');
    }

    /**
     * Show the mentee registration form.
     */
    public function showMentee()
    {
        return view('auth.register-mentee');
    }

    /**
     * Store a newly registered mentee.
     */
    public function storeMentee(Request $request)
    {
        $fields = $request->validate([
            'name'     => self::BASIC_RULE,
            'email'    => self::BASIC_RULE . '|unique:users,email',
            'password' => self::BASIC_RULE . '|confirmed',
        ]);

        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role'     => 'mentee',
        ]);

        Auth::login($user);

        return redirect()->route('profile.show.self');
    }

    /**
     * Show the mentor registration form.
     */
    public function showMentor()
    {
        return view('auth.register-mentor');
    }

    /**
     * Store a newly registered mentor with extended profile data.
     */
    public function storeMentor(Request $request)
    {
        $fields = $request->validate([
            'name'                  => self::BASIC_RULE,
            'email'                 => self::BASIC_RULE . '|unique:users,email',
            'password'              => self::BASIC_RULE . '|confirmed',
            'expertise'             => self::BASIC_RULE,
            'bio'                   => self::BASIC_RULE,
            'language'              => self::BASIC_RULE,
            'level'                 => self::BASIC_RULE,
            'style'                 => 'nullable|string',
        ]);

        $user = User::create([
            'name'       => $fields['name'],
            'email'      => $fields['email'],
            'password'   => bcrypt($fields['password']),
            'role'       => 'mentor',
            'expertise'  => $fields['expertise'],
            'bio'        => $fields['bio'],
            'language'   => $fields['language'],
            'level'      => $fields['level'],
            'style'      => $fields['style'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('profile.show.self');
    }

    /**
     * Show login form.
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'    => self::BASIC_RULE,
            'password' => self::BASIC_RULE,
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        Auth::login($user);

        return redirect()->route('profile.show.self');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
