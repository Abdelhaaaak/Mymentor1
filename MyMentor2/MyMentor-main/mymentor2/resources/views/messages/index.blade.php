{{-- resources/views/messages/index.blade.php --}}
@extends('layouts.app')
@section('title','Messagerie')
@section('content')
<div class="max-w-5xl mx-auto py-6">
  <h2 class="text-2xl font-semibold mb-4">💬 Messagerie</h2>
  <div class="flex h-[70vh] bg-white rounded-xl shadow overflow-hidden">
    {{-- Liste des contacts --}}
    <aside class="w-1/3 border-r overflow-auto">
      <ul>
        @forelse($contacts as $contact)
          <li>
            <a href="{{ route('messages.index', [$queryKey => $contact->id]) }}"
               class="flex items-center px-4 py-3 hover:bg-gray-100
                      {{ optional($selectedUser)->id === $contact->id 
                         ? 'bg-indigo-50 font-semibold' : '' }}">
              <img src="{{ $contact->profile_image_url ?? 'https://via.placeholder.com/40' }}"
                   class="w-8 h-8 rounded-full mr-3" alt="">
              <span>{{ $contact->name }}</span>
            </a>
          </li>
        @empty
          <li class="p-4 text-gray-500">
            @if(auth()->user()->role==='mentee')
              Vous ne suivez aucun mentor.
            @else
              Personne ne vous a encore envoyé de message.
            @endif
          </li>
        @endforelse
      </ul>
    </aside>

    {{-- Fil de discussion --}}
    <section class="flex-1 flex flex-col">
      <header class="px-4 py-2 border-b">
        <h3 class="text-lg">
          @if($selectedUser)
            Discussion avec {{ $selectedUser->name }}
          @else
            Sélectionnez un contact
          @endif
        </h3>
      </header>

      <div class="flex-1 p-4 overflow-auto space-y-4">
        @if($selectedUser)
          @forelse($messages as $msg)
            <div class="max-w-xs px-4 py-2 rounded-lg
                        {{ $msg->sender_id === auth()->id() 
                           ? 'bg-indigo-100 self-end' 
                           : 'bg-gray-100 self-start' }}">
              {{ $msg->content }}
              <div class="text-xs text-gray-500 mt-1">
                {{ $msg->sent_at->format('H:i') }}
              </div>
            </div>
          @empty
            <p class="text-gray-500">Pas encore de messages.</p>
          @endforelse
        @endif
      </div>

      {{-- Formulaire d’envoi --}}
      @if($selectedUser)
      <form action="{{ route('messages.store') }}" method="POST"
            class="px-4 py-2 border-t flex">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
        <input type="text" name="content" placeholder="Écrire un message…"
               class="flex-1 border rounded-full px-4 py-2 focus:outline-none"/>
        <button type="submit"
                class="ml-4 px-4 py-2 bg-green-500 text-white rounded-full">
          Envoyer
        </button>
      </form>
      @endif

    </section>
  </div>
</div>
@endsection
