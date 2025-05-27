<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionMM;
use App\Models\Message;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;   


class DashboardController extends Controller
{
    /**
     * Show the user’s dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Eager load related models
        $user->load('skills');

        // Fetch related data
        $messagesIn = $user->receivedMessages()->with('sender')->latest()->take(5)->get();
        $messagesOut = $user->sentMessages()->with('receiver')->latest()->take(5)->get();
        $sessionsMentor = $user->mentorSessions()->with('student')->latest()->take(5)->get();
        $sessionsStudent = $user->studentSessions()->with('mentor')->latest()->take(5)->get();

        // Feedback (if user is a mentor)
        $feedbacks = Feedback::where('mentor_id', $user->id)
                             ->with('author') // assuming you have a relationship to the author
                             ->latest()
                             ->take(5)
                             ->get();

        return view('dashboard', compact(
            'user',
            'messagesIn',
            'messagesOut',
            'sessionsMentor',
            'sessionsStudent',
            'feedbacks'
        ));
    }
public function mentee()
{
    $user = auth()->user();

    // 1) Statistiques
    $upcomingSessionsCount = $user->studentSessions()
                                  ->where('scheduled_at','>=', now())
                                  ->count();

    $todaySessionsCount    = $user->studentSessions()
                                  ->whereDate('scheduled_at', now())
                                  ->count();

    // 2) Toutes les sessions pour le calendrier
    $sessionsStudent = $user->studentSessions()
                            ->with('mentor')
                            ->orderBy('scheduled_at', 'desc')
                            ->get();

    // 3) Pour la messagerie
    $mentorsFollowed  = $user->following;
    $selectedMentor   = null;
    $messages         = collect();

    if ($id = request()->query('mentor')) {
        $selectedMentor = $mentorsFollowed->firstWhere('id', $id);
        if ($selectedMentor) {
            $messages = Message::where(function($q) use($user, $selectedMentor) {
                    $q->where('sender_id',   $user->id)
                      ->where('receiver_id', $selectedMentor->id);
                })->orWhere(function($q) use($user, $selectedMentor) {
                    $q->where('sender_id',   $selectedMentor->id)
                      ->where('receiver_id', $user->id);
                })->orderBy('created_at')->get();
        }
    }
     // Préparez les events pour FullCalendar
    // dans DashboardController@mentee
$calendarEvents = $sessionsStudent->map(function($r){
    return [
      'title' => $r->mentor->name.' — '.ucfirst($r->status),
      'start' => $r->scheduled_at->toDateTimeString(),
      'color' => $r->status === 'pending'   ? '#FBBF24'
               : ($r->status === 'accepted' ? '#34D399'
               : '#EF4444'),
    ];
})->toArray();

    // 4) Retourner la vue avec toutes les variables
    return view('dashboard.mentee', [
        'upcomingSessionsCount' => $upcomingSessionsCount,
        'todaySessionsCount'    => $todaySessionsCount,
        'sessionsStudent'       => $sessionsStudent,
        'mentorsFollowed'       => $mentorsFollowed,
        'selectedMentor'        => $selectedMentor,
        'messages'              => $messages,
        'calendarEvents'        => $calendarEvents,
    ]);
}


    /**
     * Show the mentor dashboard.
     */

    public function mentor()
    {
        $user = Auth::user();

        // 1️⃣ Nombre total de sessions à venir
        $upcomingCount = SessionMM::where('mentor_id', $user->id)
                            ->where('scheduled_at', '>=', now())
                            ->count();

        // 2️⃣ Nombre de sessions prévues aujourd’hui
        $todayCount = SessionMM::where('mentor_id', $user->id)
                        ->whereDate('scheduled_at', now()->toDateString())
                        ->count();

        // 3️⃣ Nombre de suivis (followers)
        $followersCount = $user->followers()->count();

        // 🔟 Liste des 5 prochaines sessions (avec la relation mentee)
        $upcomingSessions = SessionMM::where('mentor_id', $user->id)
                              ->where('scheduled_at', '>=', now())
                              ->with('mentee')
                              ->orderBy('scheduled_at')
                              ->limit(5)
                              ->get();

        // 📬 5 derniers messages reçus
        $recentMessages = Message::where('receiver_id', $user->id)
                          ->with('sender')
                          ->orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();

        // ⭐ 5 derniers feedbacks
        $recentFeedbacks = Feedback::where('mentor_id', $user->id)
                           ->with('author')
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();

        return view('dashboard.mentor', compact(
            'upcomingCount',
            'todayCount',
            'followersCount',
            'upcomingSessions',
            'recentMessages',
            'recentFeedbacks'
        ));
    }
}







