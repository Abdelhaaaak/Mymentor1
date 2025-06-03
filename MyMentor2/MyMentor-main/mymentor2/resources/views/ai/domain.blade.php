{{-- resources/views/mentor-ai/domain.blade.php --}}
@extends('layouts.app')

@section('content')
  <h2>Choisissez votre domaine</h2>

  <form method="POST" action="{{ route('mentor-ai.domain') }}">
    @csrf

    <div class="mb-3">
      <label for="interests">Intérêts</label>
      <input
        id="interests"
        type="text"
        name="interests"
        value="{{ old('interests') }}"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="strengths">Points forts</label>
      <input
        id="strengths"
        type="text"
        name="strengths"
        value="{{ old('strengths') }}"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="goals">Objectifs</label>
      <input
        id="goals"
        type="text"
        name="goals"
        value="{{ old('goals') }}"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="learning">Préférences d'apprentissage</label>
      <input
        id="learning"
        type="text"
        name="learning"
        value="{{ old('learning') }}"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="availability">Disponibilités (h/semaine)</label>
      <input
        id="availability"
        type="number"
        name="availability"
        value="{{ old('availability') }}"
        class="form-control"
        required
      >
    </div>

    <button type="submit" class="btn btn-primary">Suggérer</button>
  </form>

  @isset($domains)
    <h3 class="mt-4">Suggestions :</h3>
    <ul>
      @forelse($domains as $dom)
        <li>{{ $dom }}</li>
      @empty
        <li>Aucune suggestion obtenue.</li>
      @endforelse
    </ul>
  @endisset
@endsection
