<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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

// On définit une constante pour la base “/profile” afin de ne pas la dupliquer partout
if (! defined('PROFILE_URI')) {
    define('PROFILE_URI', '/profile');
}

// Page d’accueil (home) ou redirection si connecté
Route::get('/', function () {
    if (! auth()->check()) {
        return view('home');
    }

    return auth()->user()->role === 'mentor'
        ? redirect()->route('mentor.dashboard')
        : redirect()->route('mentee.dashboard');
})->name('home');

// Route générique /dashboard
Route::get('/dashboard', function () {
    return auth()->user()->role === 'mentor'
        ? redirect()->route('mentor.dashboard')
        : redirect()->route('mentee.dashboard');
})->middleware('auth')->name('dashboard');

// Auth (login / register / logout) – on l’inclut via require
require __DIR__ . '/auth.php'; // NOSONAR

// Routes accessibles uniquement aux invités (guest)
Route::middleware('guest')->group(function () {
    // Inscription en tant que Mentee
    Route::get('/register/mentee', [AuthController::class, 'showMentee'])
         ->name('register.mentee');
    Route::post('/register/mentee', [AuthController::class, 'storeMentee'])
         ->name('register.mentee.store');

    // Inscription en tant que Mentor
    Route::get('/register/mentor', [AuthController::class, 'showMentor'])
         ->name('register.mentor');
    Route::post('/register/mentor', [AuthController::class, 'storeMentor'])
         ->name('register.mentor.store');
});

// Profils publics (sans authentification nécessaire)
Route::get('/profiles', [UserProfileController::class, 'index'])
     ->name('profile.index');
Route::get('/profiles/{mentor}', [UserProfileController::class, 'show'])
     ->whereNumber('mentor')
     ->name('profile.show');

// Formulaire et soumission de recommandation IA (accessible publiquement)
Route::get('/recommendations', [RecommendationController::class, 'showForm'])
     ->name('recommend.form');
Route::post('/recommendations', [RecommendationController::class, 'recommend'])
     ->name('recommend.submit');

//================================================================================
// Routes protégées par le middleware “auth”
//================================================================================
Route::middleware('auth')->group(function () {
    // Dashboards Mentor/Mentee
    Route::get('/dashboard/mentee', [DashboardController::class, 'mentee'])
         ->name('mentee.dashboard');
    Route::get('/dashboard/mentor', [DashboardController::class, 'mentor'])
         ->name('mentor.dashboard');

    // Stub AIController (pour éviter 404)
    Route::get('/mentor/ai', [AIController::class, 'find'])
         ->name('mentor.ai');

    // Pages liées à MentorAI
    Route::get('/mentor-ai', [MentorAIController::class, 'showForm'])
         ->name('mentor-ai.form');
    Route::post('/mentor-ai', [MentorAIController::class, 'handleForm'])
         ->name('mentor-ai.handle');
    Route::get('/ai-test', [MentorAIController::class, 'test'])
         ->name('mentor-ai.test');

    // Mon profil (3 occurrences de PROFILE_URI)
    Route::get(PROFILE_URI, [ProfileController::class, 'edit'])
         ->name('profile.edit');
    Route::patch(PROFILE_URI, [ProfileController::class, 'update'])
         ->name('profile.update');
    Route::delete(PROFILE_URI, [ProfileController::class, 'destroy'])
         ->name('profile.destroy');

    // Afficher son propre profil
    Route::get(PROFILE_URI . '/self', [ProfileController::class, 'showSelf'])
         ->name('profile.show.self');

    // Afficher le profil d’un autre utilisateur
    Route::get(PROFILE_URI . '/{user}', [ProfileController::class, 'show'])
         ->whereNumber('user')
         ->name('profile.user.show');

    // Suivre / se désabonner d’un mentor
    Route::post('/mentors/{mentor}/follow', [FollowController::class, 'store'])
         ->name('mentor.follow');
    Route::delete('/mentors/{mentor}/unfollow', [FollowController::class, 'destroy'])
         ->name('mentor.unfollow');

    // Recherche (SearchController, si besoin)
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])
         ->name('search');

    // Gestion des compétences (SkillController)
    Route::get('/skills', [ProfileController::class, 'skills'])
         ->name('skills.index');
    Route::post('/skills', [ProfileController::class, 'storeSkill'])
         ->name('skills.store');
    Route::delete('/skills/{skill}', [ProfileController::class, 'destroySkill'])
         ->name('skills.destroy');

    // Sessions de mentorat (SessionMMController)
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

    // Messagerie (MessageController)
    Route::get('/messages', [MessageController::class, 'index'])
         ->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])
         ->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])
         ->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
         ->name('messages.destroy');

    // Feedbacks liés à une session (FeedbackController)
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

    // Notifications (NotificationController)
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
         ->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'readOne'])
         ->name('notifications.readOne');

    // Domaines IA (DomainAiController)
    Route::get('/mentor-ai/domain', [DomainAiController::class, 'form'])
         ->name('mentor-ai.domain.form');
    Route::post('/mentor-ai/domain', [DomainAiController::class, 'suggest'])
         ->name('mentor-ai.domain.suggest');
});
