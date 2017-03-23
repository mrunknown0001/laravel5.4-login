@extends('layouts.app')

@section('title', 'Page Not Found!')

@section('content')
	<h3 class="text-center">Page Not Found!</h3>
	<p class="text-center"><a href="{{ route('home') }}">Go to Home</a></p>
@endsection