@extends('admin_layout')

@section('header')

@endsection

@section('content')
<div class="content" style="margin-top:100px">
{{-- <?php phpinfo();?> --}}

@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
</div>	

@endsection