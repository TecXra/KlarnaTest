@extends('layout')

@section('header')
    <META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
@endsection

@section('content')

<div class="container main-container containerOffset">

    <div class="row">
        <div class="breadcrumbDiv col-sm-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li class="active"> Logga in</li>
            </ul>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12 col-sm-offset-3">
            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Logga in</span></h1>

             @if (count($errors) > 0)
                <div class="row">
                    <div class="col-sm-6 alert alert-danger" style="margin-left:15px;">
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
                    <div class="col-sm-6 alert alert-{{ session('flash_message.level') }}" style="margin-left:15px; margin-right:15px">
                        <ul>
                            <li>{{ session('flash_message.message') }}</li>
                        </ul>
                    </div>
                </div>
            @endif
            <div class="row userInfo">

                <div class="col-sm-6">

                    <form role="form" class="logForm" action="{{ url('login') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>E-post</label>
                            <input type="email" name="email" class="form-control" placeholder="E-post">
                        </div>
                        <div class="form-group">
                            <label>Lösenord</label>
                            <input type="password" name="password" class="form-control" placeholder="Lösenord">
                        </div>
                        {{-- <div class="checkbox">
                            <label>
                                <input type="checkbox" name="checkbox">
                                Kom ihåg mig </label>
                        </div> --}}
                        <div class="form-group">
                            <p><a title="Recover your forgotten password" href="{{ url('losenord/aterstall') }}">Glömt lösenord? </a></p>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Logga in
                        </button>
                    
                        {{-- <a class="btn btn-primary" href="account.html"><i class="fa fa-sign-in"></i> Logga in</a> --}}


                    </form>
                </div>
            </div>
            <!--/row end-->

        </div>

        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /wrapper -->

<div class="gap"></div>
@endsection

@section('footer')
<!-- include validate.js // jquery plugin   -->
<script src="assets/js/jquery.validate.js"></script>

<script>
    $().ready(function () {
        // validate the comment form when it is submitted
        $("#regForm").validate();

        // validate signup form on keyup and submit
        $(".regForm").validate({
            errorLabelContainer: $(".regForm div.error")
        });
    });
</script>
@endsection

