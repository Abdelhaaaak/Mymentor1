{{-- resources/views/recommend/results.blade.php --}}
@extends('layouts.app')

@section('title', 'Résultats de la recommandation')

@push('head')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"/>
@endpush

@section('content')
<div class="max-w-6xl mx-auto py-12 space-y-8">
  <h1 class="text-3xl font-bold text-center" data-aos="fade-down">
    🌟 Mentors recommandés
  </h1>

  @forelse($mentors as $mentor)
    <div class="bg-white rounded-xl shadow p-6 grid md:grid-cols-3 gap-6" data-aos="fade-up">
      {{-- Profil --}}
      <div class="flex items-center space-x-4">
        @if($mentor->profile_image)
          <img
            src="{{ asset('storage/'.$mentor->profile_image) }}"
            alt="Portrait de {{ $mentor->name }}"
            class="h-16 w-16 rounded-full object-cover"
          >
        @else
          <div class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center" aria-hidden="true">
            <span class="text-xl font-semibold">
              {{ strtoupper(substr($mentor->name, 0, 1)) }}
            </span>
          </div>
        @endif

        <div>
          <h2 class="text-lg font-semibold">{{ $mentor->name }}</h2>
          <p class="text-sm">
            Score : <strong>{{ $mentor->score }}</strong>
          </p>
        </div>
      </div>

      {{-- Compétences --}}
      <div class="flex flex-wrap gap-2">
        @foreach($mentor->skills as $skill)
          <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded">
            {{ $skill->name }}
          </span>
        @endforeach
      </div>

      {{-- Actions --}}
      <div class="space-y-2 text-right">
        <a href="{{ route('profile.user.show', $mentor->id) }}"
           class="text-indigo-600 hover:underline text-sm">
          🔎 Voir profil
        </a>

        @auth
          @if(auth()->user()->role === 'mentee')
            <a href="{{ route('sessions.create', $mentor->id) }}"
               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              📅 Réserver
            </a>

            <form action="{{ route('messages.store') }}" method="POST" class="mt-2">
              @csrf
              <input type="hidden" name="receiver_id" value="{{ $mentor->id }}">

              <div>
                <label for="content-{{ $mentor->id }}" class="block font-medium text-gray-700">
                  Votre message
                </label>
                <textarea
                  id="content-{{ $mentor->id }}"
                  name="content"
                  rows="2"
                  class="w-full border rounded p-2 mb-2"
                  placeholder="Votre message…"
                  required
                >{{ old('content') }}</textarea>
              </div>

              <button type="submit"
                      class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
                ✉️ Envoyer
              </button>
            </form>
          @endif
        @endauth
      </div>
    </div>
  @empty
    <p class="text-center text-red-600">
      Aucun mentor ne correspond à ces critères.
    </p>
  @endforelse
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => AOS.init({ once: true, duration: 800 }));
</script>
@endpush
