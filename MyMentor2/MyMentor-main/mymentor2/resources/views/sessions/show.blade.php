@extends('layouts.app')

@section('content')
    <h1>Session Details</h1>
    <p><strong>Mentor:</strong> {{ $session->mentor->name }}</p>
    <p><strong>Date:</strong> {{ $session->scheduled_at->format('F j, Y, g:i A') }}</p>
    <p><strong>Status:</strong> {{ $session->status }}</p>
@endsection
