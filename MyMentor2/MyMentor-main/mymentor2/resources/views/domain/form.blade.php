@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto p-6">
  <h2 class="text-2xl font-bold mb-4">Questionnaire détaillé pour mieux cerner vos inclinaisons</h2>
  <form method="POST" action="{{ route('mentor-ai.domain.suggest') }}">
    @csrf

    <div class="space-y-4">
      {{-- Question 1 --}}
      <div>
        <label class="block font-medium">1. Quelles activités (professionnelles ou de loisir) vous passionnent le plus ?</label>
        <input type="text" name="passions" value="{{ old('passions') }}" class="w-full border rounded p-2" required>
        @error('passions')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 2 --}}
      <div>
        <label class="block font-medium">2. Quelles tâches vous semblent faciles ou naturelles à réaliser ?</label>
        <input type="text" name="taches_faciles" value="{{ old('taches_faciles') }}" class="w-full border rounded p-2" required>
        @error('taches_faciles')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 3 --}}
      <div>
        <label class="block font-medium">3. Sur quels sujets perdez-vous la notion du temps ?</label>
        <input type="text" name="flow_sujets" value="{{ old('flow_sujets') }}" class="w-full border rounded p-2" required>
        @error('flow_sujets')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 4 --}}
      <div>
        <label class="block font-medium">4. Décrivez votre environnement de travail idéal (équipe, lieu, rythme).</label>
        <input type="text" name="environnement" value="{{ old('environnement') }}" class="w-full border rounded p-2" required>
        @error('environnement')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 5 --}}
      <div>
        <label class="block font-medium">5. Quelles sont vos principales valeurs personnelles au travail ?</label>
        <input type="text" name="valeurs" value="{{ old('valeurs') }}" class="w-full border rounded p-2" required>
        @error('valeurs')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 6 --}}
      <div>
        <label class="block font-medium">6. Quelles compétences techniques ou humaines maîtrisez-vous bien ?</label>
        <input type="text" name="strengths" value="{{ old('strengths') }}" class="w-full border rounded p-2" required>
        @error('strengths')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 7 --}}
      <div>
        <label class="block font-medium">7. Quels sont vos objectifs de carrière à court terme (1‑2 ans) ?</label>
        <input type="text" name="objectifs_ct" value="{{ old('objectifs_ct') }}" class="w-full border rounded p-2" required>
        @error('objectifs_ct')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 8 --}}
      <div>
        <label class="block font-medium">8. Quels sont vos objectifs à long terme (5 ans et plus) ?</label>
        <input type="text" name="objectifs_lt" value="{{ old('objectifs_lt') }}" class="w-full border rounded p-2" required>
        @error('objectifs_lt')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 9 --}}
      <div>
        <label class="block font-medium">9. Quelles nouvelles compétences ou connaissances souhaitez-vous acquérir ?</label>
        <input type="text" name="learning" value="{{ old('learning') }}" class="w-full border rounded p-2" required>
        @error('learning')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Question 10 --}}
      <div>
        <label class="block font-medium">10. Combien d’heures par semaine pouvez-vous consacrer à votre développement ?</label>
        <input type="number" name="availability" value="{{ old('availability') }}" class="w-full border rounded p-2" min="1" required>
        @error('availability')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

    </div>

    <div class="mt-6">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Obtenir mes suggestions</button>
    </div>

  </form>
</div>
@endsection