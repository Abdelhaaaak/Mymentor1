{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','MyMentor') }} – @yield('title')</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
   
    referrerpolicy="no-referrer"
  />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"/>
  <style>
    @keyframes gradient-pan {
      0%   { background-position:0% 50% }
      100% { background-position:200% 50% }
    }
    .animate-gradient-pan {
      background-size:400% 400%;
      animation:gradient-pan 20s ease infinite;
    }
  </style>
  @stack('head')
</head>
<body class="flex min-h-screen bg-gray-50">

  @auth
    <aside class="w-64 bg-white shadow-lg flex-shrink-0">
      <div class="p-6 border-b">
        <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
      </div>
      <nav class="p-4 space-y-2">
        <a href="{{ route('dashboard') }}"
           class="block px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('dashboard*')?'bg-indigo-200 font-semibold':'' }}">
          🏠 Tableau de bord
        </a>

        @if(auth()->user()->role === 'mentor')
          <a href="{{ route('mentor.dashboard') }}"
             class="block px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('mentor.dashboard')?'bg-indigo-200 font-semibold':'' }}">
            📋 Mes sessions
          </a>
          <a href="{{ route('feedback.index') }}"
             class="block px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('feedback.index')?'bg-indigo-200 font-semibold':'' }}">
            ⭐ Feedback reçus
          </a>
        @else
          <a href="{{ route('mentee.dashboard') }}"
             class="block px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('mentee.dashboard')?'bg-indigo-200 font-semibold':'' }}">
            📅 Mes réservations
          </a>
          <a href="{{ route('profile.index') }}"
             class="block px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('profile.index')?'bg-indigo-200 font-semibold':'' }}">
            👥 Nos mentors
          </a>
        @endif

        <a href="{{ route('profile.edit') }}"
           class="block px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('profile.edit')?'bg-indigo-200 font-semibold':'' }}">
          👤 Mon profil
        </a>

        <a href="{{ route('messages.index') }}"
           class="flex items-center space-x-2 px-4 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('messages*')?'bg-indigo-200 font-semibold':'' }}">
          <span>💬</span><span>Messages</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
          @csrf
          <button type="submit"
                  class="w-full text-left px-4 py-2 text-red-600 rounded hover:bg-red-100">
            🔒 Déconnexion
          </button>
        </form>
      </nav>
    </aside>
  @endauth

  <div class="flex-1 flex flex-col">
    <header class="bg-white shadow-sm">
      <div class="container mx-auto flex items-center justify-between px-6 py-4">
        <a href="{{ route('home') }}"
           class="text-2xl font-bold text-indigo-600">
          {{ config('app.name','MyMentor') }}
        </a>
     @include('partials.navbar-search')
        @guest
          <div class="space-x-4">
            <a href="{{ route('login') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
              Connexion
            </a>
            <a href="{{ route('register.mentee') }}"
               class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded hover:bg-indigo-100 transition">
              Inscription
            </a>
          </div>
        @endguest

        @auth
          <div class="flex items-center space-x-4">

            {{-- Notifications --}}
            @php
              $unread      = auth()->user()->unreadNotifications;
              $unreadCount = $unread->count();
            @endphp

            <div x-data="{ open:false }" class="relative">
              <button @click="open = !open"
                      class="relative focus:outline-none">
                🔔
                @if($unreadCount)
                  <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                    {{ $unreadCount }}
                  </span>
                @endif
              </button>

              <div x-show="open" @click.away="open = false"
                   class="absolute right-0 mt-2 bg-white shadow-lg rounded w-64 z-50">
                @forelse($unread as $notif)
                  @php
                    // Choisit la bonne clé, selon le type de notification
                    $text = $notif->data['message'] 
                          ?? $notif->data['content'] 
                          ?? 'Nouvelle notification';
                  @endphp

                  <form method="POST" action="{{ route('notifications.readOne', $notif->id) }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                      {{ $text }}
                      <span class="text-xs text-gray-400 block">
                        {{ $notif->created_at->diffForHumans() }}
                      </span>
                    </button>
                  </form>
                @empty
                  <p class="px-4 py-2 text-gray-600">Aucune notification</p>
                @endforelse


                <form method="POST" action="{{ route('notifications.readAll') }}" class="border-t">
                  @csrf
                  <button type="submit"
                          class="w-full text-left px-4 py-2 hover:bg-gray-100">
                    Marquer toutes comme lues
                  </button>
                </form>
              </div>
            </div>
            {{-- /Notifications --}}

            {{-- Trouver mon mentor (mentee) --}}
            @if(auth()->user()->role === 'mentee')
              <a href="{{ route('recommend.form') }}"
                 class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition">
                Trouver mon mentor
              </a>
              <a href="{{ route('mentor-ai.domain.form') }}"
                 class="px-4 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition">
                🧭 Choisir mon domaine
              </a>
            @endif

            {{-- Dropdown profil --}}
            <div x-data="{ open:false }" class="relative">
              <button @click="open=!open"
                      class="flex items-center space-x-2 focus:outline-none">
                <span>👤</span>
                <span>{{ auth()->user()->name }}</span>
              </button>
              <div x-show="open" @click.away="open=false"
                   class="absolute right-0 mt-2 bg-white shadow-lg rounded w-48">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 hover:bg-gray-100">Mon profil</a>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                          class="w-full text-left px-4 py-2 hover:bg-gray-100">
                    Déconnexion
                  </button>
                </form>
              </div>
            </div>

          </div>
        @endauth

      </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6">
      @yield('content')
    </main>

    <footer class="bg-white border-t">
      <div class="container mx-auto text-center py-4 text-gray-500 text-sm">
        © {{ date('Y') }} {{ config('app.name','MyMentor') }}. Tous droits réservés.
      </div>
    </footer>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>document.addEventListener('DOMContentLoaded', ()=> AOS.init({ once:true, duration:800 }));</script>
  @stack('scripts')
</body>
</html>
