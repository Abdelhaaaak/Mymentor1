@extends('layouts.app')

@section('title', 'Book Session')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Book a Session with {{ $mentor->name }}</h2>

    <form action="{{ route('sessions.store', $mentor) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Select Date & Time</label>
            <input type="datetime-local" name="scheduled_at"
                   class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-blue-400"
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Message (optional)</label>
            <textarea name="message" rows="4"
                      class="w-full border px-4 py-2 rounded focus:ring-2 focus:ring-blue-400"></textarea>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Confirm Booking
        </button>
    </form>
</div>
@endsection
