{{-- resources/views/messages/create.blade.php --}}
@extends('layouts.app')
@section('title','Envoyer un message')
@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
  <h2 class="text-xl font-bold mb-4">Envoyer un message à {{ $recipient->name }}</h2>
  <form action="{{ route('messages.store') }}" method="POST">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $recipient->id }}">
    <div class="mb-4">
      <label for="content" class="block text-sm font-medium">Message</label>
      <textarea name="content" id="content" rows="5"
                class="w-full border rounded px-3 py-2" required></textarea>
    </div>
    <button type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      Envoyer
    </button>
  </form>
</div>
@endsection
