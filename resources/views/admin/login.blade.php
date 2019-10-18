<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/css/style_admin_panel.css') }}" rel="stylesheet">

    <!-- Just for debugging purposes. -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- include pace script for automatic web page progress bar  -->

    <script>
        paceOptions = {
            elements: true
        };
    </script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
	
    <!-- **************************************************************-->
        @yield('header')

</head>
<body style="background-color: #ccc">
<div class="container">
	<div class="col-sm-4  col-sm-offset-4" style="margin-top: 150px;background-color: #fff; padding:40px 20px; border-radius: 5px">
		
		<form method="POST" action="{{ url('admin/77889') }}">
			{!! csrf_field() !!}
			<fieldset>
			<legend>Logga in till Admin Panel</legend>
			@if (count($errors) > 0)
                <div class="row">
                    <div class="col-sm-11 alert alert-danger" style="margin-left:15px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session()->has('flash_message'))
                <div class="row">
                    <div class="col-sm-11 alert alert-{{ session('flash_message.level') }}" style="margin-left:15px;">
                        <ul>
                            <li>{{ session('flash_message.message') }}</li>
                        </ul>
                    </div>
                </div>
            @endif
			<div class="form-group">
				<label for="email">E-post:</label>
				<input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
			</div>
		
			<div class="form-group">
				<label for="password">Lösenord:</label>
				<input type="password" name="password" id="password" class="form-control">
			</div>

			<br>
		
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
			</div>
			</fieldset>
		</form>
	</div>
</div>

</body>
</html>
