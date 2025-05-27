{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
  <div class="bg-white rounded-2xl shadow-xl overflow-auto" data-aos="fade-up">

    {{-- Banner + Avatar --}}
    <div class="relative h-32 bg-gradient-to-r from-indigo-600 via-teal-400 to-purple-600">
      <div class="absolute inset-x-0 bottom-0 flex justify-center transform translate-y-1/2">
        <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-indigo-100">
          @if($user->profile_image)
            <img src="{{ asset('storage/'.$user->profile_image) }}" class="w-full h-full object-cover" alt="Avatar">
          @else
            <div class="flex items-center justify-center w-full h-full">
              <span class="text-4xl font-bold text-indigo-600">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </span>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Main Info --}}
    <div class="pt-16 pb-6 text-center px-6">
      <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
      <p class="text-gray-600">{{ $user->email }}</p>
      <span class="inline-block mt-2 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
        {{ ucfirst($user->role) }}
      </span>
      {{-- Suivre / Se désabonner --}}
      @auth
        @if(auth()->user()->role === 'mentee' && $user->role === 'mentor')
          <div class="mt-4">
            @if(auth()->user()->isFollowing($user))
              <form action="{{ route('mentor.unfollow', $user) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                  Se désabonner
                </button>
              </form>
            @else
              <form action="{{ route('mentor.follow', $user) }}" method="POST" class="inline">
                @csrf
                <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                  Suivre
                </button>
              </form>
            @endif
          </div>
        @endif
      @endauth
    </div>

    {{-- Metadata --}}
    <div class="flex justify-center space-x-4 text-gray-500 text-sm px-6 mb-6">
      <span>Inscrit le : {{ optional($user->created_at)->format('d/m/Y') }}</span>
      <span>Dernière MAJ : {{ optional($user->updated_at)->format('d/m/Y') }}</span>
    </div>

    {{-- Skills --}}
    @if(!empty($skills))
      <div class="px-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">💡 Compétences</h2>
        <div class="flex flex-wrap gap-2">
          @foreach($skills as $skill)
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $skill }}</span>
          @endforeach
        </div>
      </div>
    @endif

    {{-- Mentor Details --}}
    @if($user->role === 'mentor')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 mb-6">
        <div class="p-6 bg-gray-50 rounded-lg shadow">
          <h2 class="font-semibold mb-2">🎯 Expertise</h2>
          <p>{{ $user->expertise ?? 'Non renseignée' }}</p>
        </div>
        <div class="p-6 bg-gray-50 rounded-lg shadow">
          <h2 class="font-semibold mb-2">📝 Bio</h2>
          <p>{{ $bio ?? 'Aucune bio fournie.' }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 px-6 mb-6">
        <div class="text-center bg-gray-50 p-4 rounded-lg shadow">
          <h3 class="font-medium">🌐 Langue</h3>
          <p>{{ $user->language ?? '—' }}</p>
        </div>
        <div class="text-center bg-gray-50 p-4 rounded-lg shadow">
          <h3 class="font-medium">📈 Niveau</h3>
          <p>{{ $user->level ?? '—' }}</p>
        </div>
        <div class="text-center bg-gray-50 p-4 rounded-lg shadow">
          <h3 class="font-medium">🎨 Style</h3>
          <p>{{ $user->style ?? '—' }}</p>
        </div>
      </div>

      <div class="px-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📅 Prochains rendez-vous</h2>
        @if($upcomingSessions->isEmpty())
          <p class="text-gray-500">Aucune session à venir.</p>
        @else
          @foreach($upcomingSessions as $sess)
            <div class="mb-3">
              <p class="font-medium">{{ $sess->mentee->name }}</p>
              <p class="text-gray-600 text-sm">
                {{ \Carbon\Carbon::parse($sess->scheduled_at)->translatedFormat('d M Y H:i') }}
              </p>
            </div>
          @endforeach
        @endif
      </div>

      <div class="px-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
          ⭐ Feedbacks (Moyenne : {{ $averageRating }})
        </h2>
        @if($feedbacks->isEmpty())
          <p class="text-gray-500">Pas encore de feedbacks.</p>
        @else
          @foreach($feedbacks as $fb)
            <div class="mb-3">
              <p class="italic">“{{ $fb->comment }}”</p>
              <p class="text-sm text-gray-600">— {{ $fb->author->name }}</p>
            </div>
          @endforeach
        @endif
      </div>
    @endif

    {{-- Bottom Actions --}}
    <div class="px-6 pb-8 flex flex-wrap justify-center space-x-4">
      @auth
        @if(auth()->user()->role === 'mentee' && $user->role === 'mentor')
          <a href="{{ route('messages.create', ['recipient' => $user->id]) }}"
             class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition">
            📩 Envoyer un message
          </a>
        @endif
        @if(auth()->id() === $user->id)
          <a href="{{ route('profile.edit', $user->id) }}"
             class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            ✏️ Éditer mon profil
          </a>
        @endif
      @endauth
      <a href="{{ url()->previous() }}"
         class="px-4 py-2 border rounded hover:bg-gray-100">
        ← Retour
      </a>
    </div>

  </div>
</div>
@endsection
