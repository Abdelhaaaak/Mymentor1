<?php

namespace App\Http\Controllers;

use App\Models\SessionMM;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notifications\NewSessionRequestNotification;

class SessionMMController extends Controller
{
    /**
     * Affiche la liste des sessions de l'utilisateur (mentor ou mentee).
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'mentor') {
            $sessions = SessionMM::where('mentor_id', $user->id)
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at', 'asc')
                ->get();
        } else {
            $sessions = SessionMM::where('mentee_id', $user->id)
    ->where('scheduled_at', '>=', now())
    ->orderBy('scheduled_at', 'asc')
    ->get();

        }

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Montre le formulaire de création de réservation pour un mentor donné.
     */
    public function create(User $mentor)
    {
        return view('sessions.create', compact('mentor'));
    }

    /**
     * Stocke une nouvelle session de mentorat.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'mentor_id'    => 'required|exists:users,id',
        'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        'notes'        => 'nullable|string',
    ]);

    /** @var SessionMM $session */
    $session = SessionMM::create([
        'mentor_id'    => $data['mentor_id'],
        'mentee_id'    => auth()->id(),
        'scheduled_at' => $data['scheduled_at'],
        'notes'        => $data['notes'] ?? null,
        'status'       => 'pending',
    ]);

    // on récupère le mentor et on le notifie
    $mentor = User::findOrFail($data['mentor_id']);
    $mentor->notify(new NewSessionRequestNotification($session));

    return redirect()
           ->route('mentee.dashboard')
           ->with('success', 'Votre réservation a été créée !');
}

    /**
     * Met à jour le statut d'une session.
     */
      public function updateStatus(SessionMM $session, Request $request)
    {
        $data = $request->validate([
          'status' => 'required|in:pending,accepted,declined',
          'notes'  => 'nullable|string',
        ]);

        $session->update($data);

        return back()->with('success', 'Session mise à jour.');
    }

    /**
     * Supprime une session (optionnel).
     */
    public function destroy(SessionMM $session)
    {
        // Autorisation possible
        $session->delete();
        return back()->with('success', 'Session supprimée.');
    }
}