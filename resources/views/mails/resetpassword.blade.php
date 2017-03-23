@extends('layouts.app')

@section('content')
<h3 class="text-center">Password Reset</h3>
<hr/>
<a href="{{ url('/password/reset?c=' . $code . '&u=' . $id . '&t=' . uniqid($id)) }}">Reset Password Link</a>
<hr/>
<p>This reset link is valid for 30 minutes only from time of submittion.</p>

<p>Web Master</p>
@endsection