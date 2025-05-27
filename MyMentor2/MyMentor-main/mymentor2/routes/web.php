<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SessionMMController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\MentorAIController;
use App\Http\Controllers\DomainAiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FollowController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d’accueil ou redirection
Route::get('/', function () {
    if (! auth()->check()) {
        return view('home');
    }
    return auth()->user()->role === 'mentor'
        ? redirect()->route('mentor.dashboard')
        : redirect()->route('mentee.dashboard');
})->name('home');

// Redirection générique vers dashboard
Route::get('/dashboard', function () {
    return auth()->user()->role === 'mentor'
        ? redirect()->route('mentor.dashboard')
        : redirect()->route('mentee.dashboard');
})->middleware('auth')->name('dashboard');

// Auth Laravel
require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    // Inscription Mentee
    Route::get('/register/mentee', [RegisteredUserController::class, 'showMentee'])
         ->name('register.mentee');
    Route::post('/register/mentee', [RegisteredUserController::class, 'storeMentee'])
         ->name('register.mentee.store');
    // Inscription Mentor
    Route::get('/register/mentor', [RegisteredUserController::class, 'showMentor'])
         ->name('register.mentor');
    Route::post('/register/mentor', [RegisteredUserController::class, 'storeMentor'])
         ->name('register.mentor.store');
});

// Profils publics
Route::get('/profiles', [UserProfileController::class, 'index'])
     ->name('profile.index');
Route::get('/profiles/{mentor}', [UserProfileController::class, 'show'])
     ->whereNumber('mentor')
     ->name('profile.show');

// Recommandations IA
Route::get('/recommendations', [RecommendationController::class, 'showForm'])
     ->name('recommend.form');
Route::post('/recommendations', [RecommendationController::class, 'recommend'])
     ->name('recommend.submit');

Route::middleware('auth')->group(function () {
    // Dashboards
    Route::get('/dashboard/mentee', [DashboardController::class, 'mentee'])
         ->name('mentee.dashboard');
    Route::get('/dashboard/mentor', [DashboardController::class, 'mentor'])
         ->name('mentor.dashboard');

    // Stub AIController (évite l’erreur 404)
    Route::get('/mentor/ai', [AIController::class, 'find'])
         ->name('mentor.ai');

    // Autres pages IA
    Route::get('/mentor-ai', [MentorAIController::class, 'showForm'])
         ->name('mentor-ai.form');
    Route::post('/mentor-ai', [MentorAIController::class, 'handleForm'])
         ->name('mentor-ai.handle');
    Route::get('/ai-test', [MentorAIController::class, 'test'])
         ->name('mentor-ai.test');

    // Mon profil
    Route::get('/profile', [ProfileController::class, 'edit'])
         ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
         ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
         ->name('profile.destroy');
    Route::get('/profile/self', [ProfileController::class, 'showSelf'])
         ->name('profile.show.self');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])
         ->whereNumber('user')
         ->name('profile.user.show');

    // Suivre / se désabonner
  
    Route::post   ('/mentors/{mentor}/follow',   [FollowController::class, 'store'])->name('mentor.follow');
    Route::delete ('/mentors/{mentor}/unfollow', [FollowController::class, 'destroy'])->name('mentor.unfollow');


     Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // Compétences
    Route::get('/skills', [ProfileController::class, 'skills'])
         ->name('skills.index');
    Route::post('/skills', [ProfileController::class, 'storeSkill'])
         ->name('skills.store');
    Route::delete('/skills/{skill}', [ProfileController::class, 'destroySkill'])
         ->name('skills.destroy');

    // Sessions de mentorat
    Route::prefix('sessions')->group(function () {
        Route::get('/', [SessionMMController::class, 'index'])
             ->name('sessions.index');
        Route::post('/', [SessionMMController::class, 'store'])
             ->name('sessions.store');
        Route::patch('/{session}/status', [SessionMMController::class, 'updateStatus'])
             ->name('sessions.update');
    });
    Route::get('/book/{mentor}', [SessionMMController::class, 'create'])
         ->whereNumber('mentor')
         ->name('sessions.create');

    // Messagerie
    Route::get('/messages', [MessageController::class, 'index'])
         ->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])
         ->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])
         ->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
         ->name('messages.destroy');

    // Feedbacks liés à une session
    Route::get(
        '/mentors/{mentor}/sessions/{session}/feedback/create',
        [FeedbackController::class, 'create']
    )->name('feedback.create');
    Route::post(
        '/mentors/{mentor}/sessions/{session}/feedback',
        [FeedbackController::class, 'store']
    )->name('feedback.store');
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])
         ->name('feedback.destroy');
    Route::get('/feedback', [FeedbackController::class, 'index'])
         ->name('feedback.index');

    // Notifications
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
         ->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'readOne'])
         ->name('notifications.readOne');

    // Domaines IA
    Route::get('/mentor-ai/domain', [DomainAiController::class, 'form'])
         ->name('mentor-ai.domain.form');
    Route::post('/mentor-ai/domain', [DomainAiController::class, 'suggest'])
         ->name('mentor-ai.domain.suggest');
});
