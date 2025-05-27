{{-- resources/views/search/results.blade.php --}}

@extends('layouts.app')

@section('title', 'Recherche : ' . $q)

@section('content')
  <div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-4">
      Résultats pour « {{ $q }} »
    </h1>

    @if($results->isEmpty())
      <p class="text-gray-600">Aucun résultat trouvé.</p>
    @else
      <ul class="space-y-2">
        @foreach($results as $item)
          <li class="p-4 border rounded-lg hover:shadow-sm transition">
            {{-- Adaptez la route et la propriété affichée --}}
            <a href="{{ url()->current() }}" class="text-indigo-600 font-medium">
              {{ $item->titre }}
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
@endsection
