@extends('layouts.app')

@section('title', 'Password Reset')

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<hr/>
			@include('includes.error')
			@include('includes.success')
			<div class="panel panel-default">
				<div class="panel-heading">
					Password Reset
				</div>
				<div class="panel-body">
					<form action="{{ route('post_reset_password') }}" method="POST" autocomplete="off">
						{{ csrf_field() }}
						<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
							<input type="text" name="email" id="email" class="form-control" placeholder="Email Address" autofocus />
							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif

						</div>
						<div class="form-group">
							<button class="btn btn-primary">Reset Password</button>
						</div>
					</form>
					<p>
						<a href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i></a> |
						<a href="{{ route('login') }}">Login</a> |
						<a href="{{ route('register') }}">Register</a>
					</p>
				</div>
			</div>
		</div>
	</div>
@endsection