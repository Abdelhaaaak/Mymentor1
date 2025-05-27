{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','MyMentor') }} – @yield('title','Welcome')</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"/>

  <style>
    /* Background gradient animation */
    @keyframes gradient-pan {
      0% { background-position:0% 50%; }
      100% { background-position:200% 50%; }
    }
    .animate-gradient-pan {
      background-size:300% 300%;
      animation:gradient-pan 15s ease infinite;
    }
    /* Fade-in-up effect */
    @keyframes fadeInUp {
      0% { opacity:0; transform:translateY(20px); }
      100% { opacity:1; transform:translateY(0); }
    }
    .fade-in-up {
      opacity:0;
      animation:fadeInUp .8s ease-out forwards;
    }
  </style>
  @stack('head')
</head>

<body class="relative antialiased flex flex-col min-h-screen overflow-x-hidden">

  {{-- Animated background --}}
  <div class="fixed inset-0 -z-10 bg-gradient-to-r from-indigo-600 via-teal-400 to-indigo-600 animate-gradient-pan"></div>

  {{-- Navbar --}}
  <nav class="relative z-20 bg-white bg-opacity-90 backdrop-blur-md shadow-md">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      {{-- Logo + Brand --}}
      <a href="{{ route('home') }}" class="flex items-center space-x-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8">
        <span class="font-bold text-2xl text-gray-800 hover:text-indigo-600 transition">MyMentor</span>
      </a>
    <div >
            
        </div>
      {{-- Desktop links --}}
      <div class="hidden md:flex items-center space-x-8">
        <a href="{{ route('profile.index') }}"
           class="text-gray-700 hover:text-indigo-600 transition">Our Mentors</a>
    

      {{-- Call-to-action --}}
      <div class="hidden md:block space-x-4">
        <a href="{{ route('login') }}"
               class="px-5 py-2 bg-gradient-to-r from-green-400 to-green-600 text-white font-semibold rounded-full shadow-lg
                  hover:from-green-500 hover:to-green-700 transform hover:scale-105 transition">
              Connexion
            </a>
            <a href="{{ route('register.mentee') }}"
               class="px-5 py-2 bg-gradient-to-r from-green-400 to-blue-600 text-white font-semibold rounded-full shadow-lg
                  hover:from-green-500 hover:to-green-700 transform hover:scale-105 transition">
              Inscription
            </a>
      </div>

      {{-- Mobile menu toggle --}}
      <button class="md:hidden text-gray-700 focus:outline-none" @click="openMobile = !openMobile">
        <i class="fa fa-bars fa-lg"></i>
      </button>
    </div>

    {{-- Mobile menu --}}
    <div x-data="{ openMobile: false }" x-show="openMobile" class="md:hidden bg-white shadow-lg">
      <a href="{{ route('profile.index') }}"
         class="block px-6 py-3 text-gray-700 hover:bg-indigo-50">Our Mentors</a>
      <a href="{{ route('mentor-ai.form') }}"
         class="block px-6 py-3 text-gray-700 hover:bg-indigo-50">AI Mentor</a>
      <a href="{{ route('recommend.form') }}"
         class="block px-6 py-3 text-green-600 font-semibold hover:bg-green-50">Find My Mentor</a>
    </div>
  </nav>

  {{-- Main content --}}
  <main class="flex-1 container mx-auto px-4 py-12">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="bg-white bg-opacity-90 py-6 mt-auto">
    <div class="container mx-auto text-center text-gray-600">
      © {{ date('Y') }} MyMentor. All rights reserved.
    </div>
  </footer>

  {{-- AOS init --}}
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script> AOS.init({ once: true, duration: 800 }); </script>
  @stack('scripts')
</body>
</html>
