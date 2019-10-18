@extends('layout')

<!-- Main Content -->
@section('content')
<div class="container main-container" style="margin-top:240px">
    <div class="row">
        <div class="breadcrumbDiv col-sm-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li class="active"> Återställ lösenord</li>
            </ul>
        </div>
    </div>

    <div class="row userInfo">
        <div class="col-sm-9 col-sm-offset-3">
            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Återställ lösenord</span></h1>
            
            <br>
                
            <div class="row userInfo">
                <div class="col-sm-6">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-group" role="form" method="POST" action="{{ url('/losenord/email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label>E-post Adress</label>
                           {{--  <input type="password" name="password" class="form-control" placeholder="Lösenord"> --}}
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-post" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Skicka återställningslänk
                            </button>
                        </div> 

                        {{-- <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
