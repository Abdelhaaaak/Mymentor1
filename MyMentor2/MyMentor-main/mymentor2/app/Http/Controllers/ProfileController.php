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
     * Validation rule for any field that is optional ("nullable")
     * but must be a string if present. By centralizing this literal
     * into a constant, future changes (e.g., adding max length) can
     * be made in one place.
     *
     * @var string
     */
    protected const NULLABLE_STRING = 'nullable|string';

    /**
     * Display the form for editing the authenticated user’s profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user   = Auth::user();
        $skills = $user->skills()->pluck('name')->implode(', ');
        return view('profile.edit', compact('user', 'skills'));
    }

    /**
     * Update the authenticated user’s profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate incoming request data. Note the use of self::NULLABLE_STRING
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'expertise'     => self::NULLABLE_STRING . '|max:255',
            'bio'           => self::NULLABLE_STRING,
            'language'      => self::NULLABLE_STRING,
            'level'         => self::NULLABLE_STRING,
            'style'         => self::NULLABLE_STRING,
            'skills'        => self::NULLABLE_STRING,
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Handle profile image upload if present
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // Store new image and set its path in $data
            $data['profile_image'] = $request
                ->file('profile_image')
                ->store('profiles', 'public');
        }

        // Update user with validated data
        $user->update($data);

        // Sync "skills" pivot table if skills were provided
        if (! empty($data['skills'])) {
            // Split comma-separated string into an array of names
            $names = array_filter(
                array_map('trim', explode(',', $data['skills']))
            );

            // Retrieve existing skill names → their IDs
            $existing = Skill::whereIn('name', $names)
                             ->pluck('name', 'id')
                             ->flip();

            // Determine which names are new
            $newNames = array_diff($names, $existing->values()->all());

            // Collect IDs for existing skills
            $ids = $existing->keys()->all();

            // For each new skill name, create it and add its ID
            foreach ($newNames as $name) {
                $ids[] = Skill::create(['name' => $name])->id;
            }

            // Sync the pivot table with the final list of skill IDs
            $user->skills()->sync($ids);
        }

        // Redirect back to the user’s profile page with a success message
        return redirect()
            ->route('profile.show', $user->id)
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Delete (destroy) the authenticated user’s profile (account).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        // Delete profile image if it exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Optionally cascade delete pivot (skills), sessions, messages…
        $user->delete();

        return redirect()->route('home')
                         ->with('success', 'Votre compte a bien été supprimé.');
    }

    /**
     * Redirect the authenticated user to their own public profile page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showSelf()
    {
        return redirect()->route('profile.user.show', Auth::id());
    }

    /**
     * Display the specified user’s public profile, including skills,
     * upcoming sessions, feedbacks, and average rating (for mentors).
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with('skills')->findOrFail($id);

        // Convert the user’s skills to a simple array of names
        $skills = $user->skills->pluck('name')->toArray();
        $bio    = $user->bio;

        if ($user->role === 'mentor') {
            // Load upcoming sessions (future) and feedbacks for mentors
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
