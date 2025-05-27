@extends('layouts.app')

@section('title', "Feedback pour {$mentor->name}")

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
  <h1 class="text-2xl font-bold mb-4">Feedback pour {{ $mentor->name }}</h1>

  <form method="POST"
        action="{{ route('feedback.store', ['mentor'=>$mentor->id, 'session'=>$session->id]) }}">
    @csrf

    <input type="hidden" name="mentor_session_id" value="{{ $session->id }}">
    <input type="hidden" name="author_id"         value="{{ auth()->id() }}">

    <div class="mb-4">
      <label for="rating" class="block mb-1">Note (1–5)</label>
      <select name="rating" id="rating" class="w-full border rounded p-2">
        @for($i=1; $i<=5; $i++)
          <option value="{{ $i }}" {{ old('rating')==$i ? 'selected':'' }}>
            {{ $i }}
          </option>
        @endfor
      </select>
      @error('rating')<p class="text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
      <label for="comment" class="block mb-1">Commentaire</label>
      <textarea name="comment" id="comment" rows="4"
                class="w-full border rounded p-2">{{ old('comment') }}</textarea>
      @error('comment')<p class="text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end space-x-4">
      <a href="{{ route('mentor.dashboard', ['mentor'=>$mentor->id]) }}"
         class="px-4 py-2 border rounded hover:bg-gray-100">Annuler</a>
      <button type="submit"
              class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded">
        Envoyer
      </button>
    </div>
  </form>
</div>
@endsection
