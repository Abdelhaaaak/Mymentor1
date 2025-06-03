<?php

namespace App\Http\Controllers;

use App\Models\SessionMM;
use App\Models\Message;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard générique (ne sert que si vous voulez une page “dashboard” commune).
     */
    public function index()
    {
        $user = Auth::user();
        $user->load('skills');

        $messagesIn  = $user->receivedMessages()->with('sender')
                            ->latest()->take(5)->get();
        $messagesOut = $user->sentMessages()->with('receiver')
                            ->latest()->take(5)->get();

        $sessionsMentor  = $user->mentorSessions()->with('student')
                               ->latest()->take(5)->get();
        $sessionsStudent = $user->studentSessions()->with('mentor')
                                ->latest()->take(5)->get();

        $feedbacks = collect();
        if ($user->role === 'mentor') {
            $feedbacks = Feedback::where('mentor_id', $user->id)
                                 ->with('author')
                                 ->latest()
                                 ->take(5)
                                 ->get();
        }

        return view('dashboard', compact(
            'user',
            'messagesIn',
            'messagesOut',
            'sessionsMentor',
            'sessionsStudent',
            'feedbacks'
        ));
    }

    /**
     * Dashboard d’un mentee.
     */
    public function mentee()
    {
        $user = Auth::user();

        // 1) Statistiques
        $upcomingSessionsCount = $user->studentSessions()
                                      ->where('scheduled_at', '>=', now())
                                      ->count();

        $todaySessionsCount = $user->studentSessions()
                                   ->whereDate('scheduled_at', now())
                                   ->count();

        // 2) Toutes les sessions pour le calendrier
        $sessionsStudent = $user->studentSessions()
                                ->with('mentor')
                                ->orderBy('scheduled_at', 'desc')
                                ->get();

        // 3) Messagerie entre mentee et mentors suivis
        $mentorsFollowed = $user->following;
        $selectedMentor  = null;
        $messages        = collect();

        if ($mentorId = request()->query('mentor')) {
            $selectedMentor = $mentorsFollowed->firstWhere('id', $mentorId);
            if ($selectedMentor) {
                $messages = Message::where(function ($q) use ($user, $selectedMentor) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $selectedMentor->id);
                })->orWhere(function ($q) use ($user, $selectedMentor) {
                    $q->where('sender_id', $selectedMentor->id)
                      ->where('receiver_id', $user->id);
                })->orderBy('created_at')->get();
            }
        }

        // 4) Construire les events pour FullCalendar (sans nested ternary)
        $calendarEvents = $sessionsStudent->map(function ($r) {
            $status = $r->status;

            if ($status === 'pending') {
                $color = '#FBBF24';
            } elseif ($status === 'accepted') {
                $color = '#34D399';
            } else {
                $color = '#EF4444';
            }

            return [
                'title' => $r->mentor->name . ' — ' . ucfirst($status),
                'start' => $r->scheduled_at->toDateTimeString(),
                'color' => $color
            ];
        })->toArray();

        return view('dashboard.mentee', [
            'upcomingSessionsCount' => $upcomingSessionsCount,
            'todaySessionsCount'    => $todaySessionsCount,
            'sessionsStudent'       => $sessionsStudent,
            'mentorsFollowed'       => $mentorsFollowed,
            'selectedMentor'        => $selectedMentor,
            'messages'              => $messages,
            'calendarEvents'        => $calendarEvents
        ]);
    }

    /**
     * Dashboard d’un mentor.
     */
    public function mentor()
    {
        $user = Auth::user();

        // 1) Compter les sessions à venir pour ce mentor
        $upcomingCount = SessionMM::where('mentor_id', $user->id)
                                 ->where('scheduled_at', '>=', now())
                                 ->count();

        // 2) Compter les sessions prévues pour aujourd’hui
        $todayCount = SessionMM::where('mentor_id', $user->id)
                               ->whereDate('scheduled_at', now()->toDateString())
                               ->count();

        // 3) Nombre de followers
        $followersCount = $user->followers()->count();

        // 4) Liste des 5 prochaines sessions
        $upcomingSessions = SessionMM::where('mentor_id', $user->id)
                                     ->where('scheduled_at', '>=', now())
                                     ->with('mentee')
                                     ->orderBy('scheduled_at')
                                     ->limit(5)
                                     ->get();

        // 5) 5 derniers messages reçus
        $recentMessages = Message::where('receiver_id', $user->id)
                                 ->with('sender')
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get();

        // 6) 5 derniers feedbacks reçus
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
