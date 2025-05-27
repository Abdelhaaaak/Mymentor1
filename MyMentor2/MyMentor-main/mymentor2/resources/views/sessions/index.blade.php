{{-- resources/views/sessions/index.blade.php --}}

@extends('layouts.app')

@section('title', 'My Sessions')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-3xl font-bold mb-6">My Sessions</h1>

  @forelse($sessions as $session)
    <div class="bg-white rounded-lg shadow p-6 mb-4 flex justify-between items-center">
      {{-- Informations sur la session --}}
      <div>
        <h3 class="text-lg font-semibold">
          @if(auth()->user()->role === 'mentor')
            With {{ $session->mentee->name }}
          @else
            With {{ $session->mentor->name }}
          @endif
        </h3>

        <p class="text-sm text-gray-500">
          {{ $session->scheduled_at->format('d M Y · H:i') }}
        </p>

        @if($session->notes)
          <p class="text-sm mt-2"><strong>Notes:</strong> {{ $session->notes }}</p>
        @endif
      </div>

      {{-- Pour le mentor : actions Accept / Decline si en pending --}}
      @if(auth()->user()->role === 'mentor' && $session->status === 'pending')
        <div class="flex space-x-2">
          {{-- ACCEPT --}}
        <form action="{{ route('sessions.update', $session) }}" method="POST">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="accepted">
          <button type="submit"
                  class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
            ✅ Accept
          </button>
        </form>

        {{-- DECLINE --}}
        <form action="{{ route('sessions.update', $session) }}" method="POST">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="declined">
          <button type="submit"
                  class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
            ❌ Decline
          </button>
        </form>

        </div>

      {{-- Pour le mentee (ou pour toute session déjà confirmée/cancelled) : badge de statut --}}
      @else
        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                     {{ $session->status === 'confirmed'
                         ? 'bg-green-100 text-green-700'
                         : ($session->status === 'cancelled'
                             ? 'bg-red-100 text-red-700'
                             : 'bg-yellow-100 text-yellow-700')
                     }}">
          {{ ucfirst($session->status) }}
        </span>
      @endif

    </div>
  @empty
    <p class="text-gray-600">You have no upcoming sessions.</p>
  @endforelse
</div>
@endsection
