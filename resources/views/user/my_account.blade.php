@extends('layout')

@section('header')
    {{-- <link href="assets/css/ion.checkRadio.css" rel="stylesheet">
    <link href="assets/css/ion.checkRadio.cloudy.css" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.minimalect.min.css') }}" media="screen"/>
@endsection

@section('content')

<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li class="active"> Mitt Konto</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="fa fa-unlock-alt"></i> Mitt konto </span></h1>

            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">

                    <h2 class="block-title-2"><span>Välkommen till ditt konto. Här kan du hantera din profil och dina ordrar.</span>
                    </h2>
                    <ul class="myAccountList row">
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="Ordrar" href="{{ url('orderlista') }}"><i
                                    class="fa fa-calendar"></i> Order historik </a></div>
                        </li>
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="My addresses" href="{{ url('adresser') }}"><i
                                    class="fa fa-map-marker"></i> Mina adresser</a></div>
                        </li>
                        {{-- <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="Add address" href="{{ url('ny_adress') }}"> <i
                                    class="fa fa-edit"> </i> Lägg till ny adress</a></div>
                        </li> --}}
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="Personal information"
                                                                  href="{{ url('konto_installningar') }}"><i class="fa fa-cog"></i>
                                Konto Inställningar</a></div>
                        </li>
                        
                    </ul>
                    <div class="clear clearfix"></div>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
@endsection