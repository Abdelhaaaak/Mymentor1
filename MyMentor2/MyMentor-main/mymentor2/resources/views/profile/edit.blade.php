{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="flex items-center justify-center h-screen px-4 bg-gray-50">
  <div class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-auto transition-transform duration-500 hover:scale-105" data-aos="zoom-in">
    <div class="flex justify-between items-center mb-8" data-aos="fade-down">
      <h1 class="text-3xl font-extrabold text-gray-800">✏️ Modifier mon profil</h1>
      <button type="submit" form="profile-form" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition transform hover:scale-105">
        Mettre à jour
      </button>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-800 rounded" data-aos="fade-right">
        {{ session('success') }}
      </div>
    @endif

    {{-- Back to Dashboard --}}
    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline text-sm mb-6 inline-block" data-aos="fade-left">
      ← Retour au tableau de bord
    </a>

    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="100">
      @csrf
      @method('PATCH')

      {{-- Grid wrapper: 2 columns on md+ --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Left column --}}
        <div class="space-y-6">
          {{-- Avatar --}}
          <div class="flex items-center space-x-4" data-aos="fade-up" data-aos-delay="150">
            <div class="h-24 w-24 rounded-full overflow-hidden border shadow-sm">
              <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('images/avatar.png') }}" alt="Avatar" class="h-full w-full object-cover">
            </div>
            <label for="profile_image" class="text-sm font-medium text-gray-700">Changer l’avatar</label>
            <input type="file" name="profile_image" id="profile_image" class="block text-sm text-gray-700" />
          </div>
          @error('profile_image')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror

          {{-- Name --}}
          <div data-aos="fade-up" data-aos-delay="200">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input id="name" name="name" value="{{ old('name', $user->name) }}" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400" required>
            @error('name')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>

          {{-- Email --}}
          <div data-aos="fade-up" data-aos-delay="250">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400" required>
            @error('email')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>

          {{-- Expertise --}}
          <div data-aos="fade-up" data-aos-delay="300">
            <label for="expertise" class="block text-sm font-medium text-gray-700 mb-1">Expertise / Domaine</label>
            <input id="expertise" name="expertise" value="{{ old('expertise', $user->expertise) }}" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-400">
            @error('expertise')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>

          {{-- Skills --}}
          @if($user->role === 'mentor')
          <div data-aos="fade-up" data-aos-delay="350">
            <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Compétences (virgule)</label>
            <input id="skills" name="skills" value="{{ old('skills', $skills) }}" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
            @error('skills')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>
          @endif

          {{-- Bio --}}
          <div data-aos="fade-up" data-aos-delay="400">
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
            <textarea id="bio" name="bio" rows="4" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Right column: selects --}}
        <div class="space-y-6">
          <div data-aos="fade-up" data-aos-delay="450">
            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Langue</label>
            <select id="language" name="language" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-purple-400">
              <option value="" @if(old('language', $user->language)==='') selected @endif>-- Choisir --</option>
              <option value="Français" @if(old('language', $user->language)==='Français') selected @endif>Français</option>
              <option value="Anglais" @if(old('language', $user->language)==='Anglais') selected @endif>Anglais</option>
              <option value="Espagnol" @if(old('language', $user->language)==='Espagnol') selected @endif>Espagnol</option>
              <option value="Autre" @if(old('language', $user->language)==='Autre') selected @endif>Autre</option>
            </select>
            @error('language')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>

          <div data-aos="fade-up" data-aos-delay="500">
            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
            <select id="level" name="level" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-purple-400">
              <option value="Débutant" @if(old('level', $user->level)==='Débutant') selected @endif>Débutant</option>
              <option value="Intermédiaire" @if(old('level', $user->level)==='Intermédiaire') selected @endif>Intermédiaire</option>
              <option value="Avancé" @if(old('level', $user->level)==='Avancé') selected @endif>Avancé</option>
              <option value="Expert" @if(old('level', $user->level)==='Expert') selected @endif>Expert</option>
            </select>
            @error('level')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>

          <div data-aos="fade-up" data-aos-delay="550">
            <label for="style" class="block text-sm font-medium text-gray-700 mb-1">Style</label>
            <select id="style" name="style" class="block w-full border rounded px-3 py-2 focus:ring-2 focus:ring-purple-400">
              <option value="Formel" @if(old('style', $user->style)==='Formel') selected @endif>Formel</option>
              <option value="Informel" @if(old('style', $user->style)==='Informel') selected @endif>Informel</option>
              <option value="Décontracté" @if(old('style', $user->style)==='Décontracté') selected @endif>Décontracté</option>
              <option value="Professionnel" @if(old('style', $user->style)==='Professionnel') selected @endif>Professionnel</option>
            </select>
            @error('style')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
          </div>
        </div>

      </div> {{-- end grid --}}

      {{-- Submit --}}
      <div class="mt-8 flex justify-center" data-aos="fade-up" data-aos-delay="600">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
          Mettre à jour
        </button>
      </div>
    </form>

  </div>
</div>
@endsection
