@if(session('info'))
	<div class="alert alert-info text-center fade in top-space">
		<a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<b>{{ session('info') }}</b>
	</div>
@endif