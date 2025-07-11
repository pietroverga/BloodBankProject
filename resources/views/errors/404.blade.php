@extends('layouts.error')

@section('title', 'Page Not Found')

@section('content')
    <div class="text-center mt-20">
        <h1 class="text-6xl font-bold text-gray-800">404</h1>
        <p class="text-xl mt-4">Oops! The page you’re looking for doesn’t exist.</p>
        <a href="{{ url('/') }}" class="text-grey-700 underline mt-4 inline-block">Return Home</a>
    </div>
@endsection