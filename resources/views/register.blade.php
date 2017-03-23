@extends('layouts.app')

@section('title', 'Register')

@section('content')
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<hr/>
			<div class="panel panel-default">
				<div class="panel-heading">
					Register - All Fields are Required!
				</div>
				<div class="panel-body">
					<form action="{{ route('post_register_user') }}" method="POST" autocomplete="off">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
									<input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control text-capitalize" placeholder="First Name" autofocus />
									@if ($errors->has('first_name'))
										<span class="help-block">
											<strong>{{ $errors->first('first_name') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
									<input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control text-capitalize" placeholder="Last Name" />
									@if ($errors->has('last_name'))
										<span class="help-block">
											<strong>{{ $errors->first('last_name') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{ $errors->has('birthdate') ? 'has-error' : '' }}">
									<input type="text" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" class="form-control" placeholder="Birthdate: mm/dd/yyyy" />
									@if ($errors->has('birthdate'))
										<span class="help-block">
											<strong>{{ $errors->first('birthdate') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
									<select name="gender" id="gender" class="form-control">
										<option value="">Select Gender</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
									@if ($errors->has('gender'))
										<span class="help-block">
											<strong>{{ $errors->first('gender') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
							<input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control" placeholder="Username" />
							@if ($errors->has('username'))
								<span class="help-block">
									<strong>{{ $errors->first('username') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
							<input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email Address" />
							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('mobile_number') ? 'has-error' : '' }}">
							<input type="number" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" class="form-control" placeholder="Moible Number" />
							@if ($errors->has('mobile_number'))
								<span class="help-block">
									<strong>{{ $errors->first('mobile_number') }}</strong>
								</span>
							@endif
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
									<input type="password" name="password" id="password" class="form-control" placeholder="Password" />
									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Password" />
									@if ($errors->has('password_confirmation'))
										<span class="help-block">
											<strong>{{ $errors->first('password_confirmation') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Register</button>
						</div>

					</form>
					<p><a href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i></a> |
					<a href="{{ route('login') }}">Login</a> |
					<a href="{{ route('password_reset') }}">Forgot Password?</a></p>
				</div>
			</div>
		</div>
	</div>
@endsection