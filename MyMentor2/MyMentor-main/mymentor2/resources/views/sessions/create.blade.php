@extends('layouts.app')

@section('title', 'Book a Session')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
        Book a Session with {{ $mentor->name }}
    </h2>

    <form method="POST" action="{{ route('sessions.store') }}">
        @csrf
        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">

        {{-- Date / Time --}}
        <div class="mb-4">
            {{-- On lie maintenant le label à l’input via for="scheduled_at" --}}
            <label for="scheduled_at" class="block text-gray-700 font-medium mb-1">
                Choose a date &amp; time
            </label>
            <input
                id="scheduled_at"
                type="datetime-local"
                name="scheduled_at"
                required
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
            />
            @error('scheduled_at')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Optional Notes --}}
        <div class="mb-4">
            {{-- On lie le label à la textarea via for="notes" --}}
            <label for="notes" class="block text-gray-700 font-medium mb-1">
                Add a note (optional)
            </label>
            <textarea
                id="notes"
                name="notes"
                rows="4"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
            ></textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
        >
            Confirm Booking
        </button>
    </form>
</div>
@endsection
