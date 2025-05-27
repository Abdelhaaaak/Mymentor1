@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div class="flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white bg-opacity-90 p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">Connexion</h2>

    @if(session('status'))
      <div class="mb-4 text-green-600 font-medium">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      {{-- Email --}}
      <div class="mb-4">
        <label for="email" class="block text-gray-700 mb-2 font-medium">Email</label>
        <input id="email" name="email" type="email" required autofocus
               class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror"
               value="{{ old('email') }}">
        @error('email')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password --}}
      <div class="mb-4">
        <label for="password" class="block text-gray-700 mb-2 font-medium">Mot de passe</label>
        <input id="password" name="password" type="password" required
               class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror">
        @error('password')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Remember me --}}
      <div class="mb-4 flex items-center">
        <input type="checkbox" name="remember" id="remember" class="mr-2">
        <label for="remember" class="text-sm text-gray-700">Se souvenir de moi</label>
      </div>

      {{-- Submit --}}
      <div class="mb-6">
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
          Se connecter
        </button>
      </div>

      {{-- Forgot password --}}
      @if (Route::has('password.request'))
        <div class="text-center">
          <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
            Mot de passe oublié ?
          </a>
        </div>
      @endif
    </form>
  </div>
</div>
@endsection
