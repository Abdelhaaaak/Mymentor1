@extends('layouts.guest')

@section('title', 'Inscription Mentee')

@section('content')
<div class="max-w-md mx-auto mt-12 p-8 bg-white bg-opacity-90 rounded-2xl shadow-lg fade-in-up">
  <h1 class="text-2xl font-bold mb-6 text-center">Créer un compte Mentee</h1>
  <form method="POST" action="{{ route('register.mentee.store') }}">
    @csrf

    {{-- Name --}}
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
      <input
        id="name"
        name="name"
        value="{{ old('name') }}"
        class="mt-1 w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
        required
        autofocus
      >
      @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Email --}}
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

    {{-- Password --}}
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

    {{-- Confirm Password --}}
    <div class="mb-6">
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
      <input
        id="password_confirmation"
        name="password_confirmation"
        type="password"
        class="mt-1 w-full border rounded px-3 py-2"
      >
    </div>

    <button
      type="submit"
      class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition transform hover:scale-105"
    >
      S’inscrire comme Mentee
    </button>
  </form>

  <p class="mt-4 text-center text-sm text-gray-600">
    Vous êtes déjà inscrit ?
    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
  </p>
</div>
@endsection
