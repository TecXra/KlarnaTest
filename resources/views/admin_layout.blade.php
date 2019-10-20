<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/citydack_icon.gif') }}">
    <title>Admin Panel</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

    <link href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/css/style_admin_panel.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet">

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
<body>

<!-- Fixed navbar start -->
<div class="navbar navbar-tshop navbar-fixed-top megamenu" role="navigation">
    <div class="navbar-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6">
                    <div class="pull-left ">
<!--                         <ul class="userMenu ">
                            <li><a href="#"> <span class="hidden-xs">Admin panel</span><i
                                    class="glyphicon glyphicon-info-sign hide visible-xs "></i> </a></li>
                        </ul> -->
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 no-margin no-padding">
                    <div class="pull-right">
                        <ul class="userMenu">
                            <li><a href="{{ url('/') }}">Till framsidan</a></li>
                            <li class="dropdown hasUserMenu"><a href="#" 
                                    class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <i
                                    class="glyphicon glyphicon-log-in hide visible-xs "></i> <span class="hidden-xs">Hej, {{Auth::user()->first_name}}</span> <b
                                    class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('konto') }}"> <i class="fa fa-user"></i> Inställningar</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{ url('logout') }}"><i class="fa  fa-sign-out"></i> Logga ut</a></li>
                                </ul>
                            </li>
                            {{-- <li><a href="#"><span class="hidden-xs"> Logga ut</span> <i class="glyphicon glyphicon-user hide visible-xs "></i></a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/.navbar-top-->

    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span
                    class="sr-only"> Toggle navigation </span> <span class="icon-bar"> </span> <span
                    class="icon-bar"> </span> <span class="icon-bar"> </span></button>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-cart"><i
                    class="fa fa-shopping-cart colorWhite"> </i> <span
                    class="cartRespons colorWhite"> Cart ({{ Cart::total() }} kr)</span></button>
            <a class="navbar-brand " href="{{ url('admin/ordrar') }}" style="padding-top:15px;margin-left: 10px;"> Admin Panel</a>

            <!-- this part for mobile -->
            {{-- <div class="search-box pull-right hidden-lg hidden-md hidden-sm">
                <div class="input-group">
                    <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
                </div>
                <!-- /input-group -->

            </div> --}}
        </div>

        <!-- this part is duplicate from cartMenu  keep it for mobile -->
        

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="{{ Request::is('admin/ordrar') ? 'active' : '' }}"><a href="{{ url('admin/ordrar') }}"> Ordrar </a></li>
                <li class="dropdown dropdown-style">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Produkter <b class="caret"> </b> </a>
                    <ul class="dropdown-menu col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl">
                        <li class="{{ Request::is('admin/dack') ? 'active' : '' }}"><a href="{{ url('admin/dack') }}">Däck</a></li>
                        <li class="{{ Request::is('admin/falgar') ? 'active' : '' }}"><a href="{{ url('admin/falgar') }}">Fälgar</a></li>
                        <li class="{{ Request::is('admin/tillbehor') ? 'active' : '' }}"><a href="{{ url('admin/tillbehor') }}">Tillbehör</a></li>
                    </ul>
                </li>
                <li class="dropdown dropdown-style">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Priser <b class="caret"> </b> </a>
                    <ul class="dropdown-menu col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl">
                        <li class="{{ Request::is('admin/priser/sommardack') ? 'active' : '' }}"><a href="{{ url('admin/priser/sommardack') }}">Sommardäck</a></li>
                        <li class="{{ Request::is('admin/priser/vinterdack') ? 'active' : '' }}"><a href="{{ url('admin/priser/vinterdack') }}">Vinterdäck</a></li>
                        <li class="{{ Request::is('admin/priser/falgar') ? 'active' : '' }}"><a href="{{ url('admin/priser/falgar') }}">Fälgar</a></li>
                    </ul>
                </li>
                <li class="{{ Request::is('admin/anvandare') ? 'active' : '' }}"><a href="{{ url('admin/anvandare') }}"> Användare </a></li>
                <li class="{{ Request::is('admin/sokningar') ? 'active' : '' }}"><a href="{{ url('admin/sokningar') }}"> Sökningar </a></li>
                <li class="dropdown dropdown-style">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Bilder <b class="caret"> </b> </a>
                    <ul class="dropdown-menu col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl">
                        <li class="{{ Request::is('admin/bilder/dack') ? 'active' : '' }}"><a href="{{ url('admin/bilder/dack') }}">Däck</a></li>
                        <li class="{{ Request::is('admin/bilder/falgar') ? 'active' : '' }}"><a href="{{ url('admin/bilder/falgar') }}">Fälgar</a></li>
                    </ul>
                </li>
                <li class="{{ Request::is('admin/cms/sidor') ? 'active' : '' }}"><a href="{{ url('admin/cms/sidor') }}"> CMS </a></li>
                {{-- <li class="{{ Request::is('admin/CronJobs') ? 'active' : '' }}"><a href="{{ url('admin/CronJobs') }}"> CronJobs </a></li> --}}

                
            </ul>


            <!--- this part will be hidden for mobile version -->
            <div class="nav navbar-nav navbar-right hidden-xs">
                <!-- <div class="search-box">
                    <div class="input-group">
                        <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
                    </div>

                </div> -->
                <!--/.search-box -->
            </div>
            <!--/.navbar-nav hidden-xs-->
        </div>
        <!--/.nav-collapse -->

    </div>
    <!--/.container -->

    <!-- <div class="search-full text-right"><a class="pull-right search-close"> <i class=" fa fa-times-circle"> </i> </a>

        <div class="searchInputBox pull-right">
            <input type="search" data-searchurl="search?=" name="q" placeholder="start typing and hit enter to search"
                   class="search-input">
            <button class="btn-nobg search-btn" type="submit"><i class="fa fa-search"> </i></button>
        </div>
    </div> -->
    <!--/.search-full-->

</div>
<!-- /.Fixed navbar  -->

<!-- **************************************************************-->
		@yield('content')



<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3  col-md-3 col-sm-4 col-xs-6">
                    <h3> Support </h3>
                    <ul>
                        <li class="supportLi">
                            <p></p>
                            <h4><a class="inline" href="callto:{{ env('MAIN_PHONE') }}"> <strong> <i class="fa fa-phone"> </i> {{ env('MAIN_PHONE') }} </strong> </a></h4>
                            <h4><a class="inline" href="mailto:{{ env('MAIN_MAIL') }}"> <i class="fa fa-envelope-o"> </i>
                                {{ env('MAIN_MAIL') }} </a></h4>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Shop </h3>
                    <ul>
                        <li><a href="{{ url('') }}">Kompletta hjul</a></li>
                        <li><a href="{{ url('falgar') }}">Fälgar</a></li>
                        <li><a href="{{ url('sommardack') }}">Sommardäck</a></li>
                        <li><a href="{{ url('friktionsdack') }}">Friktionsdäck</a></li>
                        <li><a href="{{ url('dubbdack') }}">Dubbdäck</a></li>
                        {{-- <li><a href="#">MC Däck</a></li> --}}

                    </ul>

                </div>

                <div style="clear:both" class="hide visible-xs"></div>

                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Information </h3>
                    <ul class="list-unstyled footer-nav">
                       {{--  <li><a href="{{ url('')}}">FAQ?</a></li>
                        <li><a href="{{ url('') }}">Betal / Finans</a></li> --}}
                        <li><a href="{{ url('villkor') }}"> Villkor</a></li>
                        {{-- <li><a href="{{ url('samarbetspartners') }}"> Samarbetspartners </a></li> --}}
                        {{-- <li><a href="{{ url('') }}"> Om Oss </a></li> --}}
                        <li><a href="{{ url('kontakt') }}"> Kontakt</a></li>

                    </ul>
                </div>
                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Mitt konto </h3>
                    <ul>
                        <li><a href="{{ url('konto') }}"> Mitt Konto </a></li>
                        <li><a href="{{ url('adresser') }}"> Mina Adresser </a></li>
                        <li><a href="{{ url('orderlista') }}"> Order lista </a></li>
                        <li><a href="{{ url('konto_installningar') }}"> Konto inställningar </a></li>
                    </ul>
                </div>

                <div style="clear:both" class="hide visible-xs"></div>

                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12 ">
                    {{-- <h3> Nyhetsbrev </h3>
                    <ul>
                        <li>
                            <div class="input-append newsLatterBox text-center">
                                <input type="text" class="full text-center" placeholder="E-post ">
                                <button class="btn  bg-gray" type="button"> prenumerera <i
                                        class="fa fa-long-arrow-right"> </i></button>
                            </div>
                        </li>
                    </ul> --}}
                    <h3> Sociala medier </h3>
                    <ul class="social">
                       {{--  <li><a href="https://www.facebook.com/WheelZone.Dack.Falgar/"> <i class=" fa fa-facebook"> &nbsp; </i> </a></li> --}}
                        {{-- <li><a href="https://www.instagram.com/dacklineswe/"> <i class="fa fa-instagram"> &nbsp; </i> </a></li>
                        <li><a href="https://twitter.com/dacklineswe"> <i class="fa fa-twitter"> &nbsp; </i> </a></li>
                        <li><a href="https://plus.google.com/116872851748811183026"> <i class="fa fa-google-plus"> &nbsp; </i> </a></li>
                        <li><a href="https://www.youtube.com/channel/UCwOa0HxhliVPcJPSSapquzg"> <i class="fa fa-youtube"> &nbsp; </i> </a></li> --}}
                    </ul>
                </div>
            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!--/.footer-->

    <div class="footer-bottom">
        <div class="container">
            <p class="pull-left"> &copy; {{ env('APP_NAME') }} {{ date('Y')}}. All rights reserved. </p>
        </div>
    </div>
    <!--/.footer-bottom-->
</footer>


<!-- Le javascript
================================================== -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js">
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>


<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- include  parallax plugin -->
<script type="text/javascript" src="{{ asset('assets/js/jquery.parallax-1.1.js') }}"></script>

<!-- optionally include helper plugins -->
<script type="text/javascript" src="{{ asset('assets/js/helper-plugins/jquery.mousewheel.min.js') }}"></script>

<!-- include mCustomScrollbar plugin //Custom Scrollbar  -->
<script type="text/javascript" src="{{ asset('assets/js/jquery.mCustomScrollbar.js') }}"></script>

<!-- include icheck plugin // customized checkboxes and radio buttons   -->
<script type="text/javascript" src="{{ asset('assets/plugins/icheck-1.x/icheck.min.js') }}"></script>

<!-- include grid.js // for equal Div height  -->
<script src="{{ asset('assets/js/grids.js') }}"></script>

<!-- include carousel slider plugin  -->
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

<!-- jQuery select2 // custom select   -->
{{-- <script src="http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script> --}}

<!-- include touchspin.js // touch friendly input spinner component   -->
<script src="{{ asset('assets/js/bootstrap.touchspin.js') }}"></script>

<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<!-- **************************************************************-->
        @yield('footer')

<!-- include custom script for site  -->     
<script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>