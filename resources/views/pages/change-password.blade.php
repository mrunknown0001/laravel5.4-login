@extends('layouts.app')

@section('title', 'Change Password')

@section('navbar')
	@include('includes.navbar')
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			@include('includes.success')
			@include('includes.error')
			@include('includes.info')
			<div class="panel panel-default">
				<div class="panel-heading">
					Change Password
				</div>
				<div class="panel-body">
					<form action="{{ route('post_change_password') }}" method="POST">
						{{ csrf_field() }}
						<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
							<input type="password" name="password" id="password" class="form-control" placeholder="New Password" autofocus />
							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group">
							<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm New Password" />
						</div>
						<hr/>
						<div class="form-group">
							<input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter Old Password" />
						</div>
						<div class="form-group">
							<button class="btn btn-primary">Change Password</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection