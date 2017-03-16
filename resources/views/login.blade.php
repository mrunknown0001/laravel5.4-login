@extends('layouts.app')

@section('title', 'Login')

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<hr/>
			@include('includes.success')
			@include('includes.error')
			<div class="panel panel-default">
				<div class="panel-heading">
					Login
				</div>
				<div class="panel-body">
					<form action="{{ route('post_user_login') }}" method="POST" autocomplete="off">
						{{ csrf_field() }}
						<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
							<input type="text" name="username" id="username" class="form-control" placeholder="Username" autofocus />
							@if ($errors->has('username'))
								<span class="help-block">
									<strong>{{ $errors->first('username') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
							<input type="password" name="password" id="password" class="form-control" placeholder="Password" />
							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group">
							<div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" checked="true"> Remember Me
                                </label>
                            </div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Login</button>
						</div>
					</form>
					<p><a href="{{ route('register') }}">Register</a></p>
				</div>
			</div>
		</div>
	</div>
@endsection