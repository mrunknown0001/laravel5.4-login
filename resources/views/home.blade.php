@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
	<h3 class="text-center">Welcome to Home Page</h3>
	<p class="text-center"><i class="fa fa-home fa-2x" aria-hidden="true"></i></p>
	@if(Auth::check())
	<p class="text-center"><a href="{{ route('dashboard') }}">Go To Dashboard</a></p>
	@endif
@endsection