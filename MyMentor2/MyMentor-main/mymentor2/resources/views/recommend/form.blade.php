@extends('layouts.app')

@section('title', 'Trouver mon mentor')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow">
  <h1 class="text-2xl font-bold mb-6">🎯 Trouvez votre mentor idéal</h1>

  <form action="{{ route('recommend.submit') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Objectifs --}}
    <div>
      <label class="block font-medium">Vos objectifs</label>
      <textarea name="goals" rows="4"
                class="w-full border rounded p-2">@old('goals')</textarea>
    </div>

    {{-- Compétences --}}
    <div>
      <label class="block font-medium mb-2">Compétences souhaitées</label>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        @foreach($skills as $skill)
          <label class="inline-flex items-center">
            <input type="checkbox" name="skills[]" value="{{ $skill->name }}"
                   class="form-checkbox" 
                   {{ in_array($skill->name, old('skills', [])) ? 'checked' : '' }}>
            <span class="ml-2">{{ $skill->name }}</span>
          </label>
        @endforeach
      </div>
    </div>

    {{-- Langue --}}
    <div>
      <label class="block font-medium">Langue préférée</label>
      <select name="language" class="w-full border rounded p-2">
        <option value="">-- Choisir --</option>
        <option value="Français" {{ old('language')=='Français'?'selected':'' }}>Français</option>
        <option value="Anglais"   {{ old('language')=='Anglais'?'selected':'' }}>Anglais</option>
        <option value="Arabe"     {{ old('language')=='Arabe'?'selected':'' }}>Arabe</option>
      </select>
    </div>

    {{-- Niveau --}}
    <div>
      <label class="block font-medium">Niveau actuel</label>
      <select name="level" class="w-full border rounded p-2">
        <option value="">-- Sélectionner --</option>
        <option value="Débutant"      {{ old('level')=='Débutant'?'selected':'' }}>Débutant</option>
        <option value="Intermédiaire" {{ old('level')=='Intermédiaire'?'selected':'' }}>Intermédiaire</option>
        <option value="Avancé"        {{ old('level')=='Avancé'?'selected':'' }}>Avancé</option>
      </select>
    </div>

    {{-- Style --}}
    <div>
      <label class="block font-medium">Style de mentorat</label>
      <input type="text" name="style" value="{{ old('style') }}"
             class="w-full border rounded p-2" placeholder="Ex : Coaching, Q&A…">
    </div>

    {{-- Bouton --}}
    <div class="text-center">
      <button type="submit"
              class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        🔍 Rechercher
      </button>
    </div>
  </form>
</div>
@endsection
