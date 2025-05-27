@extends('layouts.app')

@section('title', 'Mon tableau de bord')

@section('content')
  {{-- Bouton Mon profil en haut à droite du contenu --}}
  <div class="flex justify-end mb-6">
    <a href="{{ route('profile.show', ['mentor' => auth()->user()->id]) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200">
      Voir mon profil
    </a>
  </div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  {{-- Conteneur global : deux colonnes sur lg, une seule sur mobile --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

    {{-- Carte Statistiques --}}
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col md:col-span-2" data-aos="fade-up">
      <div class="flex items-center mb-4">
        <i class="fa-solid fa-chart-bar text-indigo-500 mr-2"></i>
        <h3 class="text-lg font-semibold">Statistiques</h3>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center mt-4">
        <div class="flex flex-col items-center space-y-1">
          <p class="text-3xl font-bold">{{ $upcomingCount }}</p>
          <p class="text-gray-500 text-sm">Sessions à venir</p>
        </div>
        <div class="flex flex-col items-center space-y-1">
          <p class="text-3xl font-bold">{{ $todayCount }}</p>
          <p class="text-gray-500 text-sm">Aujourd’hui</p>
        </div>
        <div class="flex flex-col items-center space-y-1">
          <p class="text-3xl font-bold">{{ $followersCount }}</p>
          <p class="text-gray-500 text-sm">Suivis</p>
        </div>
      </div>
    </div>

    {{-- Carte Derniers messages --}}
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col md:col-span-2" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center mb-4">
        <i class="fa-solid fa-comment-dots text-indigo-500 mr-2"></i>
        <h3 class="text-lg font-semibold">Messages</h3>
      </div>
      <div class="flex-1 overflow-y-auto" style="max-height: 300px;">
        @forelse($recentMessages as $msg)
          <div class="mb-4 last:mb-0">
            <p class="font-medium">{{ $msg->sender->name }} :</p>
            <p class="text-gray-700">{{ Str::limit($msg->content, 80) }}</p>
            <p class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</p>
          </div>
        @empty
          <p class="text-gray-500">Aucun message récent.</p>
        @endforelse
      </div>
      <a href="{{ route('messages.index') }}" class="mt-4 self-end text-indigo-600 hover:underline text-sm">
        Voir tous les messages →
      </a>
    </div>

  </div>

  {{-- Contenu principal --}}
  <div class="space-y-6">

    {{-- Sessions à venir --}}
    <div class="bg-white rounded-2xl shadow p-6" data-aos="fade-up">
      <h3 class="text-xl font-semibold mb-4">📅 Prochains rendez-vous</h3>
      @if($upcomingSessions->isEmpty())
        <p class="text-gray-500">Pas de sessions planifiées pour l’instant.</p>
      @else
        <ul class="space-y-4">
          @foreach($upcomingSessions as $sess)
            <li class="flex justify-between items-center">
              <div>
                <p class="font-medium">{{ $sess->mentee->name }}</p>
                <p class="text-gray-600 text-sm">
                  {{ \Carbon\Carbon::parse($sess->scheduled_at)->translatedFormat('d M Y H:i') }}
                </p>
              </div>
              <span class="text-sm px-2 py-1 bg-indigo-100 text-indigo-700 rounded">
                {{ ucfirst($sess->status) }}
              </span>
            </li>
          @endforeach
        </ul>
      @endif
      <a href="{{ route('sessions.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline text-sm">
        Voir toutes mes sessions →
      </a>
    </div>

    {{-- Feedbacks récents --}}
    <div class="bg-white rounded-2xl shadow p-6" data-aos="fade-up" data-aos-delay="100">
      <h3 class="text-xl font-semibold mb-4">⭐ Derniers feedbacks</h3>
      @if($recentFeedbacks->isEmpty())
        <p class="text-gray-500">Pas encore de retours.</p>
      @else
        <ul class="space-y-4">
          @foreach($recentFeedbacks as $fb)
            <li class="border-l-4 border-yellow-400 pl-4">
              <p class="italic">“{{ Str::limit($fb->comment, 80) }}”</p>
              <div class="mt-1 text-sm text-gray-600 flex justify-between">
                <span>— {{ $fb->author->name }}</span>
                <span>{{ str_repeat('★', $fb->rating) }}</span>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
      <a href="{{ route('feedback.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline text-sm">
        Voir tous les feedbacks →
      </a>
    </div>

  </div>
</div>
@endsection
