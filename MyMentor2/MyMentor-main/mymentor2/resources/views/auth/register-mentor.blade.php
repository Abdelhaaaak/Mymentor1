@extends('layouts.guest')

@section('title', 'Inscription Mentor')

@section('content')
<div class="max-w-lg mx-auto mt-12 p-8 bg-white bg-opacity-90 rounded-2xl shadow-lg fade-in-up">
  <h1 class="text-2xl font-bold mb-6 text-center">Créer un compte Mentor</h1>
  <form method="POST" action="{{ route('register.mentor.store') }}">
    @csrf

    {{-- Nom complet --}}
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
      <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name') }}"
        class="mt-1 w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
        required
        autofocus
      >
      @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Adresse e-mail --}}
    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
      <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email') }}"
        class="mt-1 w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
        required
      >
      @error('email')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Mot de passe --}}
    <div class="mb-4">
      <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
      <input
        id="password"
        name="password"
        type="password"
        class="mt-1 w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror"
        required
      >
      @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Confirmation mot de passe --}}
    <div class="mb-4">
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
      <input
        id="password_confirmation"
        name="password_confirmation"
        type="password"
        class="mt-1 w-full border rounded px-3 py-2"
      >
    </div>

    {{-- Expertise / Domaine --}}
    <div class="mb-4">
      <label for="expertise" class="block text-sm font-medium text-gray-700">Expertise / Domaine</label>
      <input
        id="expertise"
        name="expertise"
        type="text"
        value="{{ old('expertise') }}"
        class="mt-1 w-full border rounded px-3 py-2 @error('expertise') border-red-500 @enderror"
        required
      >
      @error('expertise')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Bio --}}
    <div class="mb-4">
      <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
      <textarea
        id="bio"
        name="bio"
        rows="3"
        class="mt-1 w-full border rounded px-3 py-2 @error('bio') border-red-500 @enderror"
        required
      >{{ old('bio') }}</textarea>
      @error('bio')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Langue préférée --}}
    <div class="mb-4">
      <label for="language" class="block text-sm font-medium text-gray-700">Langue préférée</label>
      <select
        id="language"
        name="language"
        class="mt-1 w-full border rounded px-3 py-2 @error('language') border-red-500 @enderror"
        required
      >
        <option value="">-- Choisir --</option>
        <option value="Français" {{ old('language') == 'Français' ? 'selected' : '' }}>Français</option>
        <option value="Anglais"   {{ old('language') == 'Anglais'   ? 'selected' : '' }}>Anglais</option>
        <option value="Arabe"     {{ old('language') == 'Arabe'     ? 'selected' : '' }}>Arabe</option>
      </select>
      @error('language')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Niveau actuel --}}
    <div class="mb-4">
      <label for="level" class="block text-sm font-medium text-gray-700">Niveau actuel</label>
      <select
        id="level"
        name="level"
        class="mt-1 w-full border rounded px-3 py-2 @error('level') border-red-500 @enderror"
        required
      >
        <option value="">-- Choisir --</option>
        <option value="Débutant"      {{ old('level') == 'Débutant'      ? 'selected' : '' }}>Débutant</option>
        <option value="Intermédiaire" {{ old('level') == 'Intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
        <option value="Avancé"        {{ old('level') == 'Avancé'        ? 'selected' : '' }}>Avancé</option>
      </select>
      @error('level')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Style (optionnel) --}}
    <div class="mb-6">
      <label for="style" class="block text-sm font-medium text-gray-700">Style (optionnel)</label>
      <input
        id="style"
        name="style"
        type="text"
        value="{{ old('style') }}"
        placeholder="Ex : Coaching, Q&amp;A…"
        class="mt-1 w-full border rounded px-3 py-2 @error('style') border-red-500 @enderror"
      >
      @error('style')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <button
      type="submit"
      class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition transform hover:scale-105"
    >
      S’inscrire comme Mentor
    </button>
  </form>

  <p class="mt-4 text-center text-sm text-gray-600">
    Vous avez déjà un compte ?
    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
  </p>
</div>
@endsection
