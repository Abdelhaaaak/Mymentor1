{{-- resources/views/dashboard/mentee.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon dashboard')

@push('head')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Statistiques --}}
  <div class="space-y-6">
    <div class="bg-white rounded-xl shadow p-6 text-center">
      <h3 class="text-gray-600 mb-1">Sessions à venir</h3>
      <p class="text-3xl font-bold">{{ $upcomingSessionsCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
      <h3 class="text-gray-600 mb-1">Aujourd’hui</h3>
      <p class="text-3xl font-bold">{{ $todaySessionsCount }}</p>
    </div>
  </div>

  {{-- Réservations + calendrier --}}
  <div class="lg:col-span-2 space-y-6">

    {{-- Liste de vos réservations --}}
    <div class="bg-white rounded-xl shadow p-6">
      <h3 class="font-semibold text-gray-700 mb-4">Mes réservations</h3>
      @forelse($sessionsStudent as $res)
        <div class="py-3 border-b last:border-none flex justify-between items-center">
          <div>
            <p class="font-medium">Avec {{ $res->mentor->name }}</p>
            <p class="text-sm text-gray-500">
              {{ \Carbon\Carbon::parse($res->scheduled_at)->translatedFormat('d M Y H:i') }}
            </p>
            @if($res->notes ?? false)
              <p class="text-sm mt-1"><strong>Notes :</strong> {{ $res->notes }}</p>
            @endif
          </div>
          {{-- Badge de statut --}}
          @php
            switch($res->status) {
              case 'pending':  $color = 'bg-yellow-100 text-yellow-800'; break;
              case 'accepted': $color = 'bg-green-100 text-green-800';  break;
              case 'declined': $color = 'bg-red-100 text-red-800';      break;
              default:         $color = 'bg-gray-100 text-gray-800';
            }
          @endphp
          <span class="px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
            {{ ucfirst($res->status) }}
          </span>
        </div>
      @empty
        <p class="text-gray-500">Vous n’avez encore aucune réservation.</p>
      @endforelse
    </div>

    {{-- Calendrier --}}
    <div class="bg-white rounded-xl shadow p-6">
      <h3 class="font-semibold text-gray-700 mb-4">📅 Votre calendrier</h3>
      <div id="calendar" class="h-[600px]"></div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    var events = @json($calendarEvents);
    console.log('Events chargés :', events);

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'fr',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek'
      },
      events: events,
      eventDidMount: function(info) {
        info.el.setAttribute('title', info.event.title);
      }
    });

    calendar.render();
  });
  </script>
@endpush
