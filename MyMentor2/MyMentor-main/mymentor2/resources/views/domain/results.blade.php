@extends('layouts.app')

@section('title', 'Vos suggestions de domaines')

@section('content')
<div class="max-w-3xl mx-auto py-12 space-y-8">
    <h1 class="text-3xl font-bold text-center mb-6">
        🌟 Domaines recommandés pour vous
    </h1>

    {{-- On parcourt la liste des domaines retournés par Cohere --}}
    @forelse($domains as $domain)
        <div class="bg-white p-6 rounded-xl shadow flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">{{ $domain }}</h2>
                {{-- Si l’on a mappé des institutions, on les affiche ici --}}
                @if(isset($recommendations[$domain]))
                    <ul class="mt-2 list-disc list-inside text-gray-700">
                        @foreach($recommendations[$domain] as $institut)
                            <li><strong>{{ $institut[0] }}</strong> – {{ $institut[1] }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @empty
        <p class="text-center text-red-600">Aucun domaine n’a pu être suggéré.</p>
    @endforelse

    {{-- Lien “Recommencer” --}}
    <div class="text-center">
        <a href="{{ route('mentor-ai.domain.form') }}"
           class="text-blue-600 hover:underline">
            ← Retour au formulaire
        </a>
    </div>
</div>
@endsection
