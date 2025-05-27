{{-- resources/views/mentor-ai/domain.blade.php --}}
@extends('layouts.app')

@section('content')
  <h2>Choisissez votre domaine</h2>

  <form method="POST" action="{{ route('mentor-ai.domain') }}">
    @csrf

    <div class="mb-3">
      <label>Intérêts</label>
      <input type="text" name="interests" value="{{ old('interests') }}" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Points forts</label>
      <input type="text" name="strengths" value="{{ old('strengths') }}" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Objectifs</label>
      <input type="text" name="goals" value="{{ old('goals') }}" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Préférences d'apprentissage</label>
      <input type="text" name="learning" value="{{ old('learning') }}" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Disponibilités (h/semaine)</label>
      <input type="number" name="availability" value="{{ old('availability') }}" class="form-control" required>
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
