@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h3>Account Confirmation and Activation</h3>
		<hr/>
		<p>Click the link below to confirm/activate account.</p>
		<p><a href="{{ url('/user/activity/confirm?c=' . $code . '&i=' . $id_no . '&t=' .uniqid(time()) . '&u=' . uniqid($id_no)) }}" target="_blank">Confirm and Activate Account</a></p>
		<hr/>
		<p>This activation link is valid for 30 minutes only from the time you register.</p>
		<p>Web Master</p>
	</div>
</div>
@endsection