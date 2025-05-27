@extends('layouts.app')

@section('title', 'Mentors')

@section('content')
<div 
    class="container mx-auto px-4 py-6" 
    x-data="{ search: '' }"                 {{-- Init Alpine --}}
>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tous les mentors</h1>
    </div>

    {{-- Search bar --}}
    <div class="mb-6">
        <input
            x-model="search"
            type="text"
            placeholder="Rechercher mentors..."
            class="w-full md:w-1/3 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring"
        />
    </div>

    {{-- Grid of mentor cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($mentors as $mentor)
            <div
                x-show="
                  search === '' 
                  || '{{ $mentor->name }}'.toLowerCase().includes(search.toLowerCase())
                "
                x-transition
                class="bg-white p-4 shadow rounded-lg relative"
            >
                <h3 class="font-semibold text-lg">{{ $mentor->name }}</h3>
                @if($mentor->expertise)
                    <p class="text-sm text-gray-600">{{ $mentor->expertise }}</p>
                @endif

                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('profile.show', $mentor) }}"
                       class="text-blue-600 hover:underline text-sm">
                        Voir le profil
                    </a>
                    <a href="{{ route('sessions.create', $mentor) }}"
                       class="inline-block bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                        Book session
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Message quand aucun résultat --}}
    <div 
        x-show="
          Array.from($el.querySelectorAll('[x-show]'))
               .filter(el => el.style.display !== 'none').length === 0
        "
        class="mt-6 text-center text-gray-500"
    >
        Aucun mentor trouvé pour « <span class="font-semibold" x-text="search"></span> ».
    </div>
</div>
@endsection
