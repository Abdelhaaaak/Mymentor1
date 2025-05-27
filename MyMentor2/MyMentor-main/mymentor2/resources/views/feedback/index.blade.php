{{-- resources/views/feedback/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes feedbacks reçus')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
  <h1 class="text-2xl font-bold mb-6">Feedbacks reçus</h1>

  @forelse($feedbacks as $fb)
    <div class="border-b py-4">
      <p class="font-semibold">
        De : {{ $fb->author->name }}
        <span class="text-gray-500 text-sm">— {{ $fb->created_at->format('d/m/Y H:i') }}</span>
      </p>
      <p>Note : {{ $fb->rating }} / 5</p>
      <p>{{ $fb->comment }}</p>
    </div>
  @empty
    <p class="text-gray-600">Vous n'avez reçu aucun feedback pour le moment.</p>
  @endforelse
</div>
@endsection
