<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SessionMM;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create(User $mentor, SessionMM $session)
    {
        // Ne pas commenter pour la prod, utile juste pour debug :
        // dd($mentor->id, $session->id, $session->mentor_id);
        abort_unless($session->mentor_id === $mentor->id, 404);

        return view('feedback.create', compact('mentor', 'session'));
    }

    public function store(Request $request, User $mentor, SessionMM $session)
    {
        abort_unless($session->mentor_id === $mentor->id, 404);

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Feedback::create([
            'mentor_session_id' => $session->id,
            'author_id'         => auth()->id(),
            'rating'            => $data['rating'],
            'comment'           => $data['comment'],
        ]);

        return redirect()
               ->route('mentor.dashboard', $mentor)
               ->with('success', 'Merci pour votre avis !');
    }

    public function destroy(Feedback $feedback)
    {
        $this->authorize('delete', $feedback);
        $feedback->delete();

        return back()->with('success', 'Feedback supprimé.');
    }
    /**
 * Affiche tous les feedbacks reçus pour le mentor connecté.
 */
public function index()
{
    $mentor = auth()->user();  // doit être un mentor
    // On charge tous les feedbacks dont la session appartient à ce mentor
    $feedbacks = Feedback::whereHas('session', function($q) use ($mentor) {
        $q->where('mentor_id', $mentor->id);
    })
    ->orderBy('created_at', 'desc')
    ->get();

    return view('feedback.index', compact('feedbacks'));
}

}
