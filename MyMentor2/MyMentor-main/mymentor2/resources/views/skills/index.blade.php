@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Skills</h2>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('skills.store') }}" class="mb-6">
        @csrf
        <div class="flex items-center space-x-2">
            <input type="text" name="name" class="border p-2 rounded w-full" placeholder="New skill" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
        </div>
    </form>

    <ul class="space-y-2">
        @foreach($skills as $skill)
            <li class="flex justify-between items-center border-b pb-1">
                <span>{{ $skill->name }}</span>
                <form method="POST" action="{{ route('skills.destroy', $skill->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
