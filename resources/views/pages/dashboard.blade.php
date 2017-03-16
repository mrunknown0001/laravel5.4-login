@extends('layouts.app')

@section('title', 'Dashboard')

@section('navbar')
	@include('includes.navbar')
@endsection

@section('content')
	<h3 class="text-center">Welcome, {{ Auth::user()->first_name }}!</h3>
	<p class="text-center">Your ID Number: {{ Auth::user()->id_no }}</p>
@endsection