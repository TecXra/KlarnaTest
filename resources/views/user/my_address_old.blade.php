@extends('layout')

@section('header')
{{--     <link href="assets/css/ion.checkRadio.css" rel="stylesheet">
    <link href="assets/css/ion.checkRadio.cloudy.css" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.minimalect.min.css') }}" media="screen"/>
@endsection

@section('content')

<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('konto') }}"> Mitt Konto</a></li>
                <li class="active"> Mina Adresser</li>
            </ul>
        </div>
    </div>
    <!--/.row-->


    <div class="row">

        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="fa fa-map-marker"></i> Mina Adresser </span></h1>

            <div class="row userInfo">

                <div class="col-lg-12">
                    <h2 class="block-title-2"> Dina adresser är listade nedan. </h2>

                    <p> Se till att uppdatera din information vid ändringar.</p>
                </div>

                <div class="w100 clearfix">

                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Faktura Adress</strong></h3>
                            </div>
                            <div class="panel-body">
                                <ul>
                                    <li><span class="address-name"> <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong></span></li>
                                    {{-- <li><span class="address-company"> Gustavssons Bil & Däck</span></li> --}}
                                    <li><span class="address-line1"> {{ Auth::user()->billing_street_address }}</span></li>
                                    <li><span class="address-line2"> {{ Auth::user()->billing_postal_code }} {{ Auth::user()->billing_city }}, {{ strtoupper(Auth::user()->billing_country) }} </span></li>
                                    <li><span> <strong>Tel.nr</strong> : {{ Auth::user()->phone }} </span></li>
                                </ul>
                            </div>
                            <div class="panel-footer panel-footer-address"><a href="{{ url('uppdatera_adress/1') }}" class="btn btn-sm btn-success"> 
                                <i class="fa fa-edit"> </i> Ändra </a> 
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Leverans Adress</strong></h3>
                            </div>
                            <div class="panel-body">
                                <ul>
                                    <li><span class="address-name"> <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong></span></li>
                                    {{-- <li><span class="address-company"> Gustavssons Bil & Däck</span></li> --}}
                                    <li><span class="address-line1"> {{ Auth::user()->shipping_street_address }}</span></li>
                                    <li><span class="address-line2"> {{ Auth::user()->shipping_postal_code }} {{ Auth::user()->shipping_city }}, {{ strtoupper(Auth::user()->shipping_country) }} </span></li>
                                    <li><span> <strong>Tel.nr</strong> : {{ Auth::user()->phone }} </span></li>
                                </ul>
                            </div>
                            <div class="panel-footer panel-footer-address"><a href="{{ url('uppdatera_adress/2') }}" class="btn btn-sm btn-success"> 
                                <i class="fa fa-edit"> </i> Ändra </a> 
                            </div>
                        </div>
                    </div>

                </div>
                <!--/.w100-->

                {{-- <div class="col-lg-12 clearfix">
                    <a class="btn   btn-primary" href="{{ url('ny_adress') }}"><i class="fa fa-plus-circle"></i> Ange Ny Adresss </a>
                </div> --}}

                <div class="col-lg-12 clearfix">
                    <ul class="pager">
                        {{-- <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Gå till Shop </a>
                        </li> --}}
                        <li class="next pull-left"><a href="{{ asset('konto') }}">&larr; Tillbaka till konto</a></li>
                    </ul>
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
