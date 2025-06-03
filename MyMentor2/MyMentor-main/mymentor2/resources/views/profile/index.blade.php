{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Liste des mentors')

@section('content')
<div 
    class="container mx-auto px-4 py-6" 
    x-data="{ search: '' }"  {{-- Initialisation d’Alpine.js --}}
>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tous les mentors</h1>
    </div>

    {{-- Barre de recherche --}}
    <div class="mb-6">
        <input
            x-model="search"
            type="text"
            placeholder="Rechercher un mentor..."
            class="w-full md:w-1/3 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring"
        />
    </div>

    {{-- Grille des cartes de mentor --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($mentors as $mentor)
            <div
                x-show="
                  search === '' 
                  || '{{ $mentor->name }}'.toLowerCase().includes(search.toLowerCase())
                "
                x-transition
                class="bg-white p-4 shadow rounded-lg flex flex-col"
            >
                <div class="flex items-center mb-4">
                    <img
                        src="{{ $mentor->profile_image_url ?? 'https://via.placeholder.com/48' }}"
                        alt="{{ $mentor->name }}"
                        class="w-12 h-12 rounded-full mr-3 object-cover"
                    />
                    <div>
                        <h3 class="font-semibold text-lg">{{ $mentor->name }}</h3>
                        @if($mentor->expertise)
                            <p class="text-sm text-gray-600">{{ $mentor->expertise }}</p>
                        @endif
                    </div>
                </div>

                <p class="text-sm text-gray-700 flex-1">
                    {{ Str::limit($mentor->bio ?? 'Pas de description', 100) }}
                </p>

                <div class="mt-4 flex justify-between items-center">
                    <a
                      href="{{ route('profile.show', $mentor) }}"
                      class="text-indigo-600 hover:underline text-sm"
                    >
                        Voir le profil
                    </a>
                    <a
                      href="{{ route('sessions.create', $mentor) }}"
                      class="inline-block bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition"
                    >
                        Réserver
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Message lorsqu’aucun mentor ne correspond à la recherche --}}
    <div
        x-show="
          Array.from(
            $el.querySelectorAll('[x-show]')
          ).filter(el => el.style.display !== 'none').length === 0
        "
        class="mt-6 text-center text-gray-500"
    >
        Aucun mentor trouvé pour « <span class="font-semibold" x-text="search"></span> ».
    </div>
</div>
@endsection
