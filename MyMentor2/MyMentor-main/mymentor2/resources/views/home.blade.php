{{-- resources/views/home.blade.php --}}
@extends('layouts.guest')

@section('title','Welcome')

@section('content')
  {{-- Hero --}}
  <section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-teal-500 to-indigo-600 animate-gradient-pan -z-10"></div>
    <div class="container mx-auto px-6 text-center">
      <div class="inline-block bg-white bg-opacity-90 backdrop-blur-sm rounded-3xl shadow-2xl p-12 max-w-3xl" data-aos="zoom-in">
        <h1 class="text-5xl font-extrabold mb-4 text-gray-900">Trouvez Votre Mentor Idéal</h1>
        <p class="text-xl text-gray-700 mb-8">Connectez-vous avec des experts et accélérez votre parcours.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-6">
          <a href="{{ route('register.mentee') }}"
             class="px-8 py-4 bg-gradient-to-r from-green-400 to-green-600 text-white font-semibold rounded-full shadow-lg hover:from-green-500 hover:to-green-700 transform hover:scale-105 transition duration-300">
            Je suis Mentee
          </a>
          <a href="{{ route('register.mentor') }}"
             class="px-8 py-4 bg-white border-2 border-purple-500 text-purple-600 font-semibold rounded-full shadow hover:bg-purple-50 transform hover:scale-105 transition duration-300">
            Je suis Mentor
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- Pourquoi MyMentor --}}
  <section class="py-20">
    <div class="container mx-auto px-6 flex flex-col lg:flex-row items-center gap-12">
      <div class="lg:w-1/2" data-aos="fade-right">
        <h2 class="text-3xl font-bold mb-4">Pourquoi choisir MyMentor ?</h2>
        <p class="text-gray-700 mb-6">Notre plateforme met en relation étudiants et professionnels confirmés pour des sessions 1-on-1 parfaitement adaptées à vos besoins.</p>
        <ul class="space-y-3">
          @foreach([
            'Sélection rigoureuse de nos mentors',
            'Interface simple et planning intégré',
            'Retours et notes garantissant la qualité'
          ] as $item)
          <li class="flex items-center">
            <span class="inline-block mr-3 bg-indigo-500 text-white p-2 rounded-full">
              ✔
            </span>
            <span class="text-gray-800">{{ $item }}</span>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="lg:w-1/2" data-aos="fade-left">
        <img src="{{ asset('images/showcase/mentoring1.jpg') }}"
             alt="Mentoring" class="rounded-2xl shadow-lg object-cover w-full h-80">
      </div>
    </div>
  </section>

  {{-- Les 3 étapes --}}
  <section class="bg-gray-50 py-20">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-12" data-aos="fade-up">Comment ça marche en 3 étapes</h2>
      <div class="grid md:grid-cols-3 gap-8">
        @php
          $steps = [
            ['1','Inscription rapide','Créez votre compte en moins de 2 minutes.'],
            ['2','Choix du mentor','Parcourez les profils et sélectionnez l’expert adapté.'],
            ['3','Session et suivi','Planifiez, échangez et progressez étape par étape.'],
          ];
        @endphp
        @foreach($steps as $step)
        <div class="bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}">
          <div class="text-5xl font-bold text-indigo-600 mb-4">{{ $step[0] }}</div>
          <h3 class="text-xl font-semibold mb-2">{{ $step[1] }}</h3>
          <p class="text-gray-600">{{ $step[2] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Statistiques clés --}}
  <section class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center px-6">
    @php
      $stats = [
        ['Mentors', 150], ['Sessions',1200], ['Mentees',800]
      ];
    @endphp
    @foreach($stats as $stat)
    <div class="bg-white p-6 rounded-xl shadow-lg" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
      <h3 class="text-4xl font-bold text-indigo-600">{{ $stat[1] }}+</h3>
      <p class="mt-2 text-gray-600">{{ $stat[0] }}</p>
    </div>
    @endforeach
  </section>

  {{-- Témoignages --}}
  <section class="py-20">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-8" data-aos="fade-up">Ils ont transformé leur avenir</h2>
      <div class="grid md:grid-cols-3 gap-8">
        @foreach([
          ['« Grâce à MyMentor, j’ai décroché mon premier job de rêve ! »','Clara, 28 ans'],
          ['« Mon mentor m’a aidé à structurer mon projet entrepreneurial. »','Karim, 35 ans'],
          ['« Des échanges enrichissants et une communauté bienveillante. »','Sophie, 24 ans'],
        ] as $fb)
        <blockquote class="p-6 rounded-lg shadow-lg bg-white" data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}">
          <p class="italic text-gray-800">{{ $fb[0] }}</p>
          <footer class="mt-4 font-semibold text-gray-600">— {{ $fb[1] }}</footer>
        </blockquote>
        @endforeach
      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="bg-gray-50 py-20">
    <div class="container mx-auto px-6 max-w-2xl">
      <h2 class="text-3xl font-bold text-center mb-8" data-aos="fade-up">Questions fréquentes</h2>
      @foreach([
        ['Comment choisir mon mentor ?', 'Parcourez les profils, consultez les avis et choisissez selon vos objectifs.'],
        ['Quels sont les tarifs ?', 'Chaque mentor fixe ses propres tarifs, visibles sur son profil.'],
        ['Comment laisser un avis ?', 'Après chaque session, vous pouvez noter et commenter votre expérience.'],
      ] as $qa)
      <div class="mb-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
        <h3 class="font-semibold text-gray-800 mb-1">{{ $qa[0] }}</h3>
        <p class="text-gray-600">{{ $qa[1] }}</p>
      </div>
      @endforeach
    </div>
  </section>

  {{-- Call to Action final --}}
  <section class="text-center py-20">
    <h2 class="text-3xl font-bold mb-6" data-aos="fade-up">Prêt·e à faire passer votre carrière au niveau supérieur ?</h2>
    <a href="{{ route('register.mentee') }}"
       class="inline-block px-8 py-4 bg-indigo-600 text-white font-semibold rounded-full shadow-lg hover:bg-indigo-700 transform hover:scale-105 transition duration-300"
       data-aos="zoom-in" data-aos-delay="200">
      Inscrivez-vous maintenant
    </a>
  </section>
@endsection

@push('scripts')
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', ()=> AOS.init({ once:true, duration:800 }));
  </script>
@endpush
