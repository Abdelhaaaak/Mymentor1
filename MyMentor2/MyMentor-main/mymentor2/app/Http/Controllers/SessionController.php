<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class SessionController extends Controller
{
    // Show all sessions (for mentorés)
     public function index()
{
    $sessions = Session::where('student_id', Auth::id())
                       ->orderBy('scheduled_at', 'desc')
                       ->get();

    return view('sessions.index', compact('sessions'));
}

    // Book a new session
    public function book(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:users,id',  // Validate mentor ID
            'scheduled_at' => 'required|date|after:today',  // Validate session date
        ]);

        $session = new Session([
            'mentor_id' => $request->mentor_id,
            'mentee_id' => Auth::id(),  // The logged-in user is the mentee
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
        ]);

        $session->save();

        return redirect()->route('sessions.index')->with('status', 'Session booked successfully!');
    }

    // View a specific session
    public function show($id)
    {
        $session = Session::findOrFail($id);  // Find session by ID
        return view('sessions.show', compact('session'));
    }

    public function create(User $mentor)
{
    return view('sessions.book', compact('mentor'));
}

public function store(Request $request, User $mentor)
{
    $request->validate([
        'scheduled_at' => 'required|date|after:now',
        'message' => 'nullable|string',
    ]);

    $session = Session::create([
        'student_id' => auth()->id(),
        'mentor_id' => $mentor->id,
        'scheduled_at' => $request->scheduled_at,
        'message' => $request->message,
        'status' => 'pending',
    ]);

    return redirect()->route('sessions.index')->with('success', 'Session booked successfully!');
}
}