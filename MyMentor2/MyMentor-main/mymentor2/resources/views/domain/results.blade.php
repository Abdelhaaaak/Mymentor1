@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">

  <h2 class="text-3xl font-bold mb-4 text-gray-800">Vos secteurs suggérés</h2>

  {{-- 1) Paragraphe de synthèse --}}
  <p class="mb-6 text-gray-700">
    D'après vos réponses, voici les secteurs d'activité qui vous correspondent le mieux :
    <span class="font-semibold">{{ implode(', ', $domains) }}</span>.
    Ces domaines tirent parti de vos passions, de vos compétences et de vos objectifs.
  </p>

  {{-- 2) Liste des domaines --}}
  <ul class="list-disc list-inside mb-8">
    @foreach ($domains as $domain)
      <li class="text-gray-800">{{ $domain }}</li>
    @endforeach
  </ul>

  {{-- 3) Tableau des établissements --}}
  @if(!empty($recommendations))
    <h3 class="text-2xl font-semibold mb-3 text-gray-800">Écoles et programmes au Maroc</h3>
    <table class="w-full table-auto border-collapse mb-6">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2 text-left">Secteur</th>
          <th class="border px-4 py-2 text-left">Établissement</th>
          <th class="border px-4 py-2 text-left">Programme</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($recommendations as $domain => $schools)
          @foreach ($schools as [$ecole, $programme])
            <tr>
              <td class="border px-4 py-2 align-top">{{ $domain }}</td>
              <td class="border px-4 py-2">{{ $ecole }}</td>
              <td class="border px-4 py-2">{{ $programme }}</td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  @endif

  <a href="{{ route('mentor-ai.domain.form') }}" class="inline-block text-blue-600 hover:underline">
    ← Refaire le questionnaire
  </a>
</div>
@endsection
