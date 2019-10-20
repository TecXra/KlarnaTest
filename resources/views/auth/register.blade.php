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
                <li class="active"> Skapa konto </li>
            </ul>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12 col-sm-offset-3">
            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Skapa konto </span></h1>

            @if (count($errors) > 0)
                <div class="row userInfo">
                    <div class="col-sm-6" >
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row userInfo">

                <div class="col-sm-6">

                    <form role="form" class="regForm" action="{{ url('registrera')}}" method="POST">
                        {{ csrf_field() }}
                        <div class=" form-group">
                            <label>Förnamn</label>
                            <input title="Please enter your username (at least 3 characters)" type="text"
                                   name="first_name" class="form-control" placeholder="Förnamn" required minlength="3">
                        </div>
                        <div class=" form-group">
                            <label>Efternamn</label>
                            <input title="Please enter your username (at least 3 characters)" type="text"
                                   name="last_name" class="form-control" placeholder="Efternamn" required minlength="3">
                        </div>

                        <div class="form-group hidden">
                            <label>Företagsnamn</label>
                            <input type="text" name="company_name" id="RegisterCompanyName" class="form-control" placeholder="Företagsnamn" disabled="disabled">
                        </div>


                        <div class=" form-group">

                            <br>

                            <label class="checkbox-inline" for="checkboxes-0">
                                <input checked name="isCompany" id="private" value="0" type="radio">
                                Privat </label>
                            <label class="checkbox-inline" for="checkboxes-1">
                                <input name="isCompany" id="company" value="1" type="radio">
                                Företag </label>

                            <br>
                            <br>
                        </div>


                        <div class="form-group">
                            <label>E-post</label>
                            <input title="Please enter valid email" type="email" name="email" class="form-control"
                                   placeholder="E-post" required>
                        </div>

                        <div class=" form-group">
                            <label>Lösenord</label>
                            <input required
                                   title="Please enter your password, between 5 and 12 characters" type="password" name="password" class="form-control" placeholder="Lösenord">
                        </div>
                        <div class=" form-group">
                            <label>Bekräfta lösnord</label>
                            <input
                                   title="Please enter your password, between 5 and 12 characters" type="password" name="password_confirmation" class="form-control" placeholder="Bekräfta lösnord">
                        </div>
                        <div class="error">
                        </div>
                        
                        <div class="">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-user"></i> Skapa ett konto
                            </button>
                        </div>
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
    // $().ready(function () {
    //     // validate the comment form when it is submitted
    //     $("#regForm").validate();

    //     // validate signup form on keyup and submit
    //     $(".regForm").validate({
    //         errorLabelContainer: $(".regForm div.error")
    //     });
    // });
    
    $(document).on('ifChecked', '#company', function() {
        $('#RegisterCompanyName').parent().removeClass('hidden');
        $('#RegisterCompanyName').attr('required', true);
        $('#RegisterCompanyName').attr('disabled', false);
    });

    $(document).on('ifChecked', '#private', function() {
        $('#RegisterCompanyName').parent().addClass('hidden');
        $('#RegisterCompanyName').attr('required', false);
        $('#RegisterCompanyName').attr('disabled', true);
    });
</script>

@endsection

