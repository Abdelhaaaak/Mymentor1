<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::with('skills')
                     ->where('role', 'mentor');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name',      'ILIKE', "%{$search}%")
                  ->orWhere('email',   'ILIKE', "%{$search}%")
                  ->orWhere('expertise','ILIKE', "%{$search}%")
                  ->orWhere('bio',     'ILIKE', "%{$search}%");
            });
        }

        $mentors = User::where('role', 'mentor')->orderBy('name')->paginate(15);

    return view('profile.index', compact('mentors'));
    }

    // … your show(), toggleFollow(), etc. …

    /**
     * Display the specified user's profile.
     */
   public function show(User $mentor)
{
    // load skills relation
    $mentor->load('skills');

    // convert to array for the view
    $skills = $mentor->skills->pluck('name')->toArray();
    $bio    = $mentor->bio;

    // if mentor, also load sessions & feedbacks (optional)
    if ($mentor->role === 'mentor') {
        $upcomingSessions = $mentor->mentorSessions()
            ->where('scheduled_at', '>=', now())
            ->with('mentee')
            ->orderBy('scheduled_at')
            ->get();

        $feedbacks     = $mentor->feedbacks()->with('author')->get();
        $averageRating = round($feedbacks->avg('rating'), 1);
    } else {
        $upcomingSessions = collect();
        $feedbacks        = collect();
        $averageRating    = 0;
    }

    // pass exactly what your Blade expects
    return view('profile.show', [
        'user'             => $mentor,           // your view uses $user
        'skills'           => $skills,
        'bio'              => $bio,
        'upcomingSessions' => $upcomingSessions ?? collect(),
        'feedbacks'        => $feedbacks ?? collect(),
        'averageRating'    => $averageRating ?? 0,
    ]);
}


    public function toggleFollow(User $user)
{
    $authUser = auth()->user();

    if ($authUser->id === $user->id) {
        return back()->with('error', 'You cannot follow yourself.');
    }

    // Check if already following
    if ($authUser->following()->where('user_id', $user->id)->exists()) {
        $authUser->following()->detach($user->id);
    } else {
        $authUser->following()->attach($user->id);
    }

    return back();
}

}
