<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $mentor)
{
    $me = auth()->user();

    if ($mentor->role === 'mentor'
        && $me->id !== $mentor->id
        && ! $me->isFollowing($mentor)
    ) {
        // 1. On attache
        $me->following()->attach($mentor->id);

        // 2. On notifie le mentor
        $mentor->notify(new \App\Notifications\NewFollowerNotification($me));
    }

    return back();
}

    public function destroy(User $mentor)
    {
        $me = auth()->user();

        if ($me->isFollowing($mentor)) {
            $me->following()->detach($mentor->id);
        }

        return back();
    }
}
