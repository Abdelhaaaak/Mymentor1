{{-- resources/views/domain/form.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="max-w-xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">
      Questionnaire détaillé pour mieux cerner vos inclinaisons
    </h2>

    <form method="POST" action="{{ route('mentor-ai.domain.suggest') }}">
      @csrf

      {{-- Question 1 --}}
      <div class="mb-4">
        <x-input-label
          for="passions"
          :value="__('1. Quelles activités (professionnelles ou de loisir) vous passionnent le plus ?')"
        />
        <input
          id="passions"
          name="passions"
          type="text"
          value="{{ old('passions') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('passions')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 2 --}}
      <div class="mb-4">
        <x-input-label
          for="taches_faciles"
          :value="__('2. Quelles tâches vous semblent faciles ou naturelles à réaliser ?')"
        />
        <input
          id="taches_faciles"
          name="taches_faciles"
          type="text"
          value="{{ old('taches_faciles') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('taches_faciles')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 3 --}}
      <div class="mb-4">
        <x-input-label
          for="flow_sujets"
          :value="__('3. Sur quels sujets perdez-vous la notion du temps ?')"
        />
        <input
          id="flow_sujets"
          name="flow_sujets"
          type="text"
          value="{{ old('flow_sujets') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('flow_sujets')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 4 --}}
      <div class="mb-4">
        <x-input-label
          for="environnement"
          :value="__('4. Décrivez votre environnement de travail idéal (équipe, lieu, rythme).')"
        />
        <input
          id="environnement"
          name="environnement"
          type="text"
          value="{{ old('environnement') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('environnement')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 5 --}}
      <div class="mb-4">
        <x-input-label
          for="valeurs"
          :value="__('5. Quelles sont vos principales valeurs personnelles au travail ?')"
        />
        <input
          id="valeurs"
          name="valeurs"
          type="text"
          value="{{ old('valeurs') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('valeurs')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 6 --}}
      <div class="mb-4">
        <x-input-label
          for="strengths"
          :value="__('6. Quelles compétences techniques ou humaines maîtrisez-vous bien ?')"
        />
        <input
          id="strengths"
          name="strengths"
          type="text"
          value="{{ old('strengths') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('strengths')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 7 --}}
      <div class="mb-4">
        <x-input-label
          for="objectifs_ct"
          :value="__('7. Quels sont vos objectifs de carrière à court terme (1-2 ans) ?')"
        />
        <input
          id="objectifs_ct"
          name="objectifs_ct"
          type="text"
          value="{{ old('objectifs_ct') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('objectifs_ct')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 8 --}}
      <div class="mb-4">
        <x-input-label
          for="objectifs_lt"
          :value="__('8. Quels sont vos objectifs à long terme (5 ans et plus) ?')"
        />
        <input
          id="objectifs_lt"
          name="objectifs_lt"
          type="text"
          value="{{ old('objectifs_lt') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('objectifs_lt')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 9 --}}
      <div class="mb-4">
        <x-input-label
          for="learning"
          :value="__('9. Quelles nouvelles compétences ou connaissances souhaitez-vous acquérir ?')"
        />
        <input
          id="learning"
          name="learning"
          type="text"
          value="{{ old('learning') }}"
          class="w-full border rounded p-2"
          required
        />
        @error('learning')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Question 10 --}}
      <div class="mb-4">
        <x-input-label
          for="availability"
          :value="__('10. Combien d’heures par semaine pouvez-vous consacrer à votre développement ?')"
        />
        <input
          id="availability"
          name="availability"
          type="number"
          value="{{ old('availability') }}"
          min="1"
          class="w-full border rounded p-2"
          required
        />
        @error('availability')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Bouton “Obtenir mes suggestions” --}}
      <div class="mt-6">
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded"
        >
          Obtenir mes suggestions
        </button>
      </div>
    </form>
  </div>
@endsection
