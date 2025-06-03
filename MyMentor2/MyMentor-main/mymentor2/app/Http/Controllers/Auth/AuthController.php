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
     * Règle de validation de base pour tout champ « required|string ».
     *
     * @var string
     */
    protected const BASIC_RULE = 'required|string';

    /**
     * Affiche le formulaire d’inscription générique.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Enregistre un nouvel utilisateur (registration).
     * Ici, on n’a pas fait la distinction Mentor / Mentee,
     * mais vous pouvez dupliquer store() en storeMentor() / storeMentee().
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
        ]);

        Auth::login($user);

        return redirect()->route('profile.show.self');
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la connexion d’un utilisateur.
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'    => self::BASIC_RULE,
            'password' => self::BASIC_RULE,
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            return back()->withErrors(['email' => 'Identifiants invalides.']);
        }

        Auth::login($user);

        return redirect()->route('profile.show.self');
    }

    /**
     * Déconnecte l’utilisateur.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
