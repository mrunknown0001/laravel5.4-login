@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<h3 class="text-center">Registration Successfull</h3>
<p class="text-center"><a href="{{ route('home') }}"><i class="fa fa-home fa-2x"  aria-hidden="true"></i></a> | <a href="{{ route('login') }}">Login</a></p>
<p class="text-center">Please confirm/activate your account. We've send an activation code to your email address.</p>@endsection