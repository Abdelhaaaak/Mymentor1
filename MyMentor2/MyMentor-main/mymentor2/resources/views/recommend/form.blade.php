{{-- resources/views/recommend/form.blade.php --}}
@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">🎯 Trouvez votre mentor idéal</h2>

<form action="{{ route('recommend.submit') }}" method="POST" class="space-y-5">
  @csrf

  {{-- Objectifs (Goals) --}}
  <div>
    <x-input-label for="goals" :value="__('Vos objectifs')" />
    <textarea
      id="goals"
      name="goals"
      rows="4"
      class="w-full border rounded p-2"
    >{{ old('goals') }}</textarea>
    @error('goals')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Compétences souhaitées (Skills) --}}
  <div>
    <x-input-label for="skills" :value="__('Compétences souhaitées')" />
    <div id="skills" class="grid grid-cols-2 md:grid-cols-3 gap-2">
      @foreach($skills as $skill)
        <label class="inline-flex items-center">
          <input
            type="checkbox"
            name="skills[]"
            value="{{ $skill->name }}"
            class="form-checkbox"
            {{ in_array($skill->name, old('skills', [])) ? 'checked' : '' }}
          />
          <span class="ml-2">{{ $skill->name }}</span>
        </label>
      @endforeach
    </div>
    @error('skills')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Langue préférée (Language) --}}
  <div>
    <x-input-label for="language" :value="__('Langue préférée')" />
    <select
      id="language"
      name="language"
      class="w-full border rounded p-2"
    >
      <option value="">-- Choisir --</option>
      <option value="Français" {{ old('language') == 'Français' ? 'selected' : '' }}>Français</option>
      <option value="Anglais" {{ old('language') == 'Anglais' ? 'selected' : '' }}>Anglais</option>
      <option value="Arabe" {{ old('language') == 'Arabe' ? 'selected' : '' }}>Arabe</option>
    </select>
    @error('language')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Niveau actuel (Level) --}}
  <div>
    <x-input-label for="level" :value="__('Niveau actuel')" />
    <select
      id="level"
      name="level"
      class="w-full border rounded p-2"
    >
      <option value="">-- Sélectionner --</option>
      <option value="Débutant" {{ old('level') == 'Débutant' ? 'selected' : '' }}>Débutant</option>
      <option value="Intermédiaire" {{ old('level') == 'Intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
      <option value="Avancé" {{ old('level') == 'Avancé' ? 'selected' : '' }}>Avancé</option>
    </select>
    @error('level')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Style de mentorat (Style) --}}
  <div>
    <x-input-label for="style" :value="__('Style de mentorat')" />
    <input
      id="style"
      name="style"
      type="text"
      value="{{ old('style') }}"
      class="w-full border rounded p-2"
      placeholder="Ex : Coaching, Q&A…"
    />
    @error('style')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Bouton de soumission --}}
  <div class="text-center">
    <button
      type="submit"
      class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
    >
      🔍 Rechercher
    </button>
  </div>
</form>
@endsection
