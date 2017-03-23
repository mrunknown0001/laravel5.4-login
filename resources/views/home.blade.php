@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
	<h3 class="text-center">Welcome to <i class="fa fa-home" aria-hidden="true"></i> Page</h3>
	<p class="text-center"></p>
	@if(Auth::check())
	<p class="text-center"><a href="{{ route('dashboard') }}">Go To Dashboard</a></p>
	@else
	<p class="text-center"><a href="{{ route('login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a> | <a href="{{ route('register') }}">Register</a> | <a href="{{ route('password_reset') }}">Forgot Password?</a> | 
	<a href="{{ route('activate') }}">Activate Account</a></p>
	@endif
@endsection