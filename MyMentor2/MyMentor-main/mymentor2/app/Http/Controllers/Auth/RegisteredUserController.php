<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function showMentee()
    {
        return view('auth.register-mentee');
    }

    public function storeMentee(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'mentee',
        ]);

        auth()->attempt($request->only('email','password'));
        return redirect()->route('mentee.dashboard');
    }

    public function showMentor()
    {
        return view('auth.register-mentor');
    }

    public function storeMentor(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|confirmed|min:8',
            'expertise'  => 'nullable|string|max:255',
            'bio'        => 'nullable|string',
            'language'   => 'required|string',
            'level'      => 'required|string',
            'style'      => 'nullable|string',
        ]);

        $user = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => 'mentor',
            'expertise'   => $data['expertise'],
            'bio'         => $data['bio'],
            'language'    => $data['language'],
            'level'       => $data['level'],
            'style'       => $data['style'],
        ]);

        // Créer aussi le modèle Mentor lié, si vous avez une table mentors
        // Mentor::create(['user_id'=>$user->id, 'expertise'=>$data['expertise'], 'bio'=>$data['bio']]);

        auth()->login($user);
        return redirect()->route('mentor.dashboard');
    }
}
