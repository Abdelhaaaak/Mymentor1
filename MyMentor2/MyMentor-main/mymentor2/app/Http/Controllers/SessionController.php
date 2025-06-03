<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionController extends Controller
{
    /**
     * Affiche la liste des sessions pour l’utilisateur connecté (en tant que mentee).
     */
    public function index()
    {
        $sessions = Session::where('mentee_id', Auth::id())
                           ->orderBy('scheduled_at', 'desc')
                           ->get();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Affiche le formulaire de réservation d’une nouvelle session pour un mentor donné.
     */
    public function create(User $mentor)
    {
        return view('sessions.book', compact('mentor'));
    }

    /**
     * Réserve (crée) la session auprès du mentor.
     *
     * Note : on ne stocke plus la variable `$session` car on ne l’utilise pas après coup.
     */
    public function store(Request $request, User $mentor)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'message'      => 'nullable|string',
        ]);

        // Au lieu de :
        // $session = Session::create([...]);
        // on fait simplement :
        Session::create([
            'mentee_id'    => Auth::id(),
            'mentor_id'    => $mentor->id,
            'scheduled_at' => $request->scheduled_at,
            'message'      => $request->message,
            'status'       => 'pending',
        ]);

        return redirect()
               ->route('sessions.index')
               ->with('success', 'Session booked successfully!');
    }

    /**
     * Affiche une session spécifique.
     */
    public function show($id)
    {
        $session = Session::findOrFail($id);
        return view('sessions.show', compact('session'));
    }

    /**
     * Ancienne méthode « book » (si vous en aviez une autre),
     * vous pouvez la conserver si elle est utile, mais veillez
     * à ne pas laisser une variable `$session` non utilisée.
     */
    public function book(Request $request)
    {
        $request->validate([
            'mentor_id'    => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:today',
        ]);

        // Ici on utilise bien `$session` pour faire $session->save(), donc ce n’est pas “unused”
        $session = new Session([
            'mentor_id'    => $request->mentor_id,
            'mentee_id'    => Auth::id(),
            'scheduled_at' => $request->scheduled_at,
            'status'       => 'pending',
        ]);

        $session->save();

        return redirect()
               ->route('sessions.index')
               ->with('status', 'Session booked successfully!');
    }
}
