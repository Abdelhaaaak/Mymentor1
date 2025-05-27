<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Skill;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire d’édition du profil connecté.
     */
    public function edit()
    {
        $user   = Auth::user();
        $skills = $user->skills()->pluck('name')->implode(', ');
        return view('profile.edit', compact('user', 'skills'));
    }

    /**
     * Met à jour les données du profil connecté.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'expertise'     => 'nullable|string|max:255',
            'bio'           => 'nullable|string',
            'language'      => 'nullable|string',
            'level'         => 'nullable|string',
            'style'         => 'nullable|string',
            'skills'        => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // upload image
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles','public');
        }

        $user->update($data);

        // sync compétences pivot
        if (! empty($data['skills'])) {
            $names = array_filter(array_map('trim', explode(',', $data['skills'])));
            $existing = Skill::whereIn('name',$names)->pluck('name','id')->flip();
            $newNames = array_diff($names, $existing->values()->all());

            $ids = $existing->keys()->all();
            foreach ($newNames as $name) {
                $ids[] = Skill::create(['name'=>$name])->id;
            }

            $user->skills()->sync($ids);
        }

            return redirect()
            ->route('profile.show', $user->id)
            ->with('success', 'Profil mis à jour avec succès.');

    }

    /**
     * Supprime le profil (compte) du user.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        // supprimer image si existante
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Optionnel : cascade delete skills pivot, sessions, messages...
        $user->delete();

        return redirect()->route('home')
                         ->with('success', 'Votre compte a bien été supprimé.');
    }

    /**
     * Redirige vers /profile/{id} pour le user connecté.
     */
    public function showSelf()
    {
        return redirect()->route('profile.user.show', Auth::id());
    }

public function show($id)
{
    // Récupère l’utilisateur…
    $user = User::with('skills')->findOrFail($id);

    // Monte les tableaux skills + bio
    $skills = $user->skills->pluck('name')->toArray();
    $bio    = $user->bio;

    // Si mentor, charge aussi sessions + feedbacks
    if ($user->role === 'mentor') {
        $upcomingSessions = $user->mentorSessions()
            ->where('scheduled_at', '>=', now())
            ->with('mentee')
            ->orderBy('scheduled_at')
            ->get();

        $feedbacks = $user->feedbacks()
            ->with('author')
            ->get();

        $averageRating = round($feedbacks->avg('rating'), 1);
    } else {
        $upcomingSessions = collect();
        $feedbacks        = collect();
        $averageRating    = 0;
    }

    // Passe exactement les mêmes clés que votre vue attend
    return view('profile.show', [
        'user'             => $user,
        'skills'           => $skills,
        'bio'              => $bio,
        'upcomingSessions' => $upcomingSessions,
        'feedbacks'        => $feedbacks,
        'averageRating'    => $averageRating,
    ]);
}


}
