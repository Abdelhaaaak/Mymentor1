@extends('layouts.app')

@section('content')
    <h1>AI Recommended Mentors</h1>

    @if($recommendedMentors->isEmpty())
        <p>No mentors found based on your skills.</p>
    @else
        <ul>
            @foreach($recommendedMentors as $mentor)
                <li>
                    <strong>{{ $mentor->name }}</strong>
                    <p>{{ $mentor->email }}</p>
                    <a href="{{ route('profile.show', $mentor->id) }}">View Profile</a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
