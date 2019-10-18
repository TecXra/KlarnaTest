<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {!! SEO::generate() !!}


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset("assets/ico/apple-icon-57x57.png") }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset("assets/ico/apple-icon-60x60.png") }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset("assets/ico/apple-icon-72x72.png") }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("assets/ico/apple-icon-76x76.png") }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset("assets/ico/apple-icon-114x114.png") }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset("assets/ico/apple-icon-120x120.png") }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset("assets/ico/apple-icon-144x144.png") }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset("assets/ico/apple-icon-152x152.png") }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("assets/ico/apple-icon-180x180.png") }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset("assets/ico/android-icon-192x192.png") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("assets/ico/favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset("assets/ico/favicon-96x96.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("assets/ico/favicon-16x16.png") }}">
    <link rel="manifest" href="{{ asset("assets/ico/manifest.json") }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset("assets/ico/ms-icon-144x144.png") }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Bootstrap core CSS -->
    {{-- <link href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" rel="stylesheet"> --}}

    <!-- Custom styles for this template -->
    {{-- <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('assets/css/importFonts.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="../../../../dackline/{{ elixir('css/app.css') }}"> --}}

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webshim/1.16.0/dev/polyfiller.js"></script>
    {{-- <script src="{{ asset('assets/js/myCustomScripts/hotjar.js') }}"></script> --}}

    <!-- **************************************************************-->
        @yield('header')

</head>
<body>
<!-- Hotjar Tracking Code for https://www.hjulonline.se/ -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:617380,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-105254096-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- Modal Login start -->

<div class="modal signUpContent fade" id="ModalLogin" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h5 style="font-size: 2.9em; line-height: 24px; text-align: center;" class="modal-title-site text-center"> Logga in </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('login') }}" method="POST">
                    {{ csrf_field() }}
                    
                    <div class="form-group login-username">
                        <div>
                            <input name="email" id="login-user" class="form-control input" size="20" placeholder="E-post" type="text">
                        </div>
                    </div>
                    <div class="form-group login-password">
                        <div>
                            <input name="password" id="login-password" class="form-control input" size="20" placeholder="Lösenord" type="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            {{-- <div class="checkbox login-remember">
                                <label>
                                    <input name="rememberme" value="forever" checked="checked" type="checkbox">
                                    Kom ihåg </label>
                            </div> --}}
                        </div>
                    </div>
                    <div>
                        <div>
                            <input name="submit" class="btn  btn-block btn-lg btn-primary" value="LOGGA IN" type="submit">
                        </div>
                    </div>
                
                </form> <!--userForm-->


            </div>
            <div class="modal-footer">
                <p class="text-center"> Första besöket? <a data-toggle="modal" data-dismiss="modal" href="#ModalSignup"> Registrera dig här. </a> <br>
                    <a href="{{ url('losenord/aterstall') }}"> Glömt lösenord? </a></p>
            </div>
        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal-dialog -->

</div>
<!-- /.Modal Login -->

<!-- Modal Signup start -->
<div class="modal signUpContent fade" id="ModalSignup" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h5 style="font-size: 2.9em; line-height: 24px; text-align: center;" class="modal-title-site text-center"> Skapa konto </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('registrera')}}" method="POST">
                {{ csrf_field() }}
                    {{-- <div class="control-group"><a class="fb_button btn  btn-block btn-lg " href="#"> REGISTRERA MED
                        FACEBOOK </a></div>
                    <h5 style="padding:10px 0 10px 0;" class="text-center"> ELLER </h5> --}}

                    <div class=" form-group reg-email">
                        <div>
                            <input name="first_name" class="form-control input" size="20" placeholder="Förnamn" type="name">
                        </div>
                    </div>
                    <div class="form-group reg-email">
                        <div>
                            <input name="last_name" class="form-control input" size="20" placeholder="Efternamn" type="name">
                        </div>
                    </div>

                    <div class="form-group hidden">
                        <input type="text" name="company_name" id="ModalRegisterCompanyName" class="form-control" placeholder="Företagsnamn" disabled="disabled">
                    </div>

                    <div class="col-sm-6 form-group pull-left">

                            <label class="checkbox-inline" for="checkboxes-0">
                                <input checked name="isCompany" id="modalPrivate" value="0" type="radio">
                                Privat </label>
                            <label class="checkbox-inline" for="checkboxes-1">
                                <input name="isCompany" id="modalCompany" value="1" type="radio">
                                Företag </label>
                                
                            <br>
                            <br>
                        </div>

                        


                    <div class="form-group reg-email">
                        <div>
                            <input name="email" class="form-control input" size="20" placeholder="E-post" type="text">
                        </div>
                    </div>
                    <div class="form-group reg-password">
                        <div>
                            <input name="password" class="form-control input" size="20" placeholder="Lösenord"
                                   type="password">
                        </div>
                    </div>

                    <div class="form-group reg-password">
                        <div>
                            <input name="password_confirmation" class="form-control input" size="20" placeholder="Bekräfta lösnord" type="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            {{-- <div class="checkbox login-remember">
                                <label>
                                    <input name="rememberme" id="rememberme" value="forever" checked="checked"
                                           type="checkbox">
                                    Kom ihåg </label>
                            </div> --}}
                        </div>
                    </div>
                    <div>
                        <div>
                            <input name="submit" class="btn  btn-block btn-lg btn-primary" value="REGISTRERA" type="submit">
                        </div>
                    </div>
                </form><!--userForm-->

            </div>
            <div class="modal-footer">
                <p class="text-center"> Redan medlem? <a data-toggle="modal" data-dismiss="modal" href="#ModalLogin">
                    Logga in </a></p>
            </div>
        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal-dialog -->

</div>
<!-- /.ModalSignup End -->

<!-- Fixed navbar start -->
<div class="navbar navbar-fixed-top megamenu" role="navigation">
    <div class="navbar-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-4 col-xs-6 col-md-4">
                    <div class="pull-left ">
                        <ul class="userMenu ">
{{--                             <li><a href="#" style="margin-left:10px"> <span class="hidden-xs">HJÄLP</span><i
                                    class="glyphicon glyphicon-info-sign hide visible-xs "></i> </a></li> --}}
                            {{-- <li class="phone-number" style="margin:5px 0 0 15px; color: #FFF; font-size: 1.2em"><span> <i
                                    class="glyphicon glyphicon-phone-alt "></i></span> <span style="margin:3px 0 0 10px"> {{ App\Setting::getPhone() }}</span>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-8 col-xs-6 col-md-8 no-margin no-padding">
                    <div class="pull-right">
                        <ul class="userMenu">
                            
                            @if (Auth::check())
                                @if(Auth::user()->user_type_id === 5)
                                    <li><a href="{{ url('/admin/ordrar') }}">Till admin</a></li>
                                @endif
                                <li><a href="{{ url('konto') }}"><span class="hidden-xs"> Mitt konto</span> <i
                                    class="glyphicon glyphicon-user hide visible-xs "></i></a></li>
                                    <li class="dropdown hasUserMenu"><a href="#" 
                                        class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <i
                                        class="glyphicon glyphicon-log-in hide visible-xs "></i> <span class="hidden-xs">Hej {{Auth::user()->first_name}}</span> <b
                                        class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ url('konto') }}"> <i class="fa fa-user"></i> Konto</a></li>
                                            <li><a href="{{ url('orderlista') }}"><i class="fa  fa-calendar"></i> Ordrar</a></li>
                                            <li><a href="{{ url('adresser') }}"><i class="fa fa-map-marker"></i> Adresser</a></li>
                                            <li><a href="{{ url('konto_installningar') }}"><i class="fa fa fa-cog"></i> Inställningar</a></li>
                                            <li class="divider"></li>
                                            <li><a href="{{ url('logout') }}"><i class="fa  fa-sign-out"></i> Logga ut</a></li>
                                        </ul>
                                    </li>
                                {{-- <li><a href="#"><span class="hidden-xs"> Logga ut</span> <i class="glyphicon glyphicon-user hide visible-xs "></i></a></li> --}}

                            @else
                                <li><a href="#" data-toggle="modal" data-target="#ModalLogin"> <span class="hidden-xs">Logga in</span><i class="glyphicon glyphicon-log-in hide visible-xs "></i> </a></li>
                                <li class="hidden-xs"><a href="#" data-toggle="modal" data-target="#ModalSignup"> Skapa konto </a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/.navbar-top-->
</div>

<div class="col-xs-12 header-dotted"></div>

<div class="container" >
    <div class="row">
        <div class="col-sm-4">
            <a class"" id="logo" {{-- class="nav-brand" --}} href="{{ url('') }}"> <img  {{-- height="45" --}} src="{{ asset('images/hjulonline_logo2.png') }}" alt="Hjulonline"> </a>
        </div>

         <form id="productNameSearchForm" class="col-sm-4 pull-right" action="{{ url('sok_sortimentet') }}" method="GET" 
                style="margin-top: 25px;padding: 0 15px 10px 15px; background-color: none; " 
            >
            <div class="input-group required">
                <input required id="productNameSearch" name="soktxt" class="form-control input" size="30" placeholder="Sök efter produkt" type="text" autocomplete="off" value="{{ Request::input('soktxt')}}">
                <span class="input-group-btn">
                    <button class="btn btn-primary" id="getAddress" type="submit">Sök!</button>
                </span>
            </div>
            <div id="suggestionBox"></div>
        </form>
    </div>
</div>

<div class="custom-navbar megamenu" role="navigation">
    

    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">
                    Toggle navigation
                </span>
                <span class="icon-bar">
                </span>
                <span class="icon-bar">
                </span>
                <span class="icon-bar">
                </span>
            </button>
            <button type="button" class="navbar-toggle mobileCartButton{{ (Cart::total() > 0 ? ' mobileCartButtonHasStuff' : ' mobileCartButtonHasNoStuff') }}" data-toggle="collapse" data-target=".navbar-cart">
                <i class="fa fa-shopping-cart colorWhite"></i>
                <span class="cartRespons colorWhite">
                    <span class="hidden-xs">({{ Cart::total() }} kr)</span>
                </span>
                <span class="cartAmount">{{ count(Cart::content()) }}</span>
            </button>
            

            <!-- this part for mobile -->
            <div class="search-box pull-right hidden-lg hidden-md hidden-sm hidden">
                <div class="input-group">
                    <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
                </div>
            </div>
        </div>

        <!-- this part is duplicate from cartMenu  keep it for mobile -->
        <div class="navbar-cart  collapse ">
            <div class="cartMenu col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                @if (sizeof(Cart::content()) > 0)
                <div class="w100 miniCartTable scroll-pane">
                    <table>
                        <tbody>
                            @foreach (Cart::content() as $item)
                                <tr class="miniCartProduct">
                                    <td style="width:20%" class="miniCartProductThumb"> 
                                        <div><a href="{{ url($item->model->productType->name .'/'. $item->id) }}"> 
                                           <img src="{{ asset($item->model->productImages->first()->thumbnail_path) }}" alt="img">   
                                        </a></div>
                                    </td>
                                    <td style="width:40%">
                                        <div class="miniCartDescription"> 
                                            <h4><a href="{{ url($item->model->productType->name .'/'. $item->id) }}"> {{$item->model->product_name}} </a></h4>
                                           
                                            <div class="price"><span> {{ $item->price }} kr </span></div>
                                        </div>
                                    </td>
                                    <td style="width:10%" class="miniCartQuantity"><a> X {{ $item->qty }} </a></td>
                                    <td style="width:15%" class="miniCartSubtotal"><span> {{ $item->total }} kr </span></td>
                                    {{-- <td style="width:5%" class="delete"><a> x </a></td> --}}
                                    <td style="width:5%" class="delete">
                                        <form action="{{ url('varukorg', [$item->rowId]) }}" method="POST" class="side-by-side">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" style="border:none;background:none;">x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach    
                        </tbody>
                    </table>
                </div>
                <!--/.miniCartTable-->
                @endif


                <div class="miniCartFooter miniCartFooterInMobile text-right">
                    <h5 class="text-right subtotal"> totalt: {{ Cart::total() }} kr </h5>
                    <a class="btn btn-sm btn-danger" href="{{ url('varukorg') }}" style="font-size:18px"> <i class="fa fa-shopping-cart"> </i> SE VARUKORG </a>
                    {{-- <a class="btn btn-sm btn-primary" href="{{ url('kassa') }}"> GÅ TILL KASSA </a> --}}
                </div>
                <!--/.miniCartFooter-->

            </div>
            <!--/.cartMenu-->
        </div>
        <!--/.navbar-cart-->
        
        <div class="row">
            <div class="navbar-collapse collapse" >
                <div class="container no-padding">

                    <div class="nav navbar-nav pull-right hidden-xs ">
                        <div class="dropdown  cartMenu "><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i
                                class="fa fa-shopping-cart"> </i> <span class="cartRespons"> Varukorg ({{ Cart::total() }} kr) </span> <b
                                class="caret"> </b> </a>

                            <div class="dropdown-menu col-xs-12 col-md-4 col-lg-4 ">
                                @if (sizeof(Cart::content()) > 0)
                                <div class="w100 miniCartTable scroll-pane">
                                    <table>
                                        <tbody>
                                        @foreach (Cart::content() as $item)
                                            <tr class="miniCartProduct">
                                                <td style="width:20%" class="miniCartProductThumb"> 
                                                    <div>
                                                    @if($item->model->product_category_id !== 4)
                                                        <a href="{{ url($item->model->productType->name .'/'. $item->id) }}"> 
                                                    @else
                                                        <a>
                                                    @endif
                                                       <img src="{{ asset($item->model->productImages->first()->thumbnail_path) }}" alt="img">
                                                    </a></div>
                                                </td>
                                                <td style="width:40%">
                                                    <div class="miniCartDescription"> 
                                                        @if($item->model->product_category_id !== 4)
                                                            <h4><a href="{{ url($item->model->productType->name .'/'. $item->product_id) }}">{{$item->model->product_name}} </a></h4>
                                                        @else
                                                            <h4> {{$item->model->product_name}} </h4>
                                                        @endif

                                                        <div class="price"><span> {{ $item->price }} kr </span></div>
                                                    </div>
                                                </td>
                                                <td style="width:10%" class="miniCartQuantity"><a> X {{ $item->qty }} </a></td>
                                                <td style="width:15%" class="miniCartSubtotal"><span> {{ $item->total }} kr </span></td>
                                                {{-- <td style="width:5%" class="delete"><a> x </a></td> --}}
                                                <td style="width:5%" class="delete">
                                                    <form action="{{ url('varukorg', [$item->rowId]) }}" method="POST" class="side-by-side">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" style="border:none;background:none;">x</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach    
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                <!--/.miniCartTable-->

                                <div class="miniCartFooter text-right">
                                    <h5 class="text-right subtotal"> totalt: {{ Cart::total() }} kr </h5>
                                    <a class="btn btn-sm btn-danger" href="{{ url('varukorg') }}" style="font-size:18px"> <i class="fa fa-shopping-cart"> </i> SE VARUKORG </a>
                                    {{-- <a class="btn btn-sm btn-primary" href="{{ url('kassa') }}"> GÅ TILL KASSA </a> --}}
                                </div>
                                <!--/.miniCartFooter-->

                            </div>
                            <!--/.dropdown-menu-->
                        </div>
                        <!--/.cartMenu-->
                    

                        <div class="search-box hidden">
                            <div class="input-group">
                                <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
                            </div>
                            <!-- /input-group -->

                        </div>
                        <!--/.search-box -->
                    </div>
                    <!--/.navbar-nav hidden-xs-->
                
                    <ul class="nav navbar-nav pull-left">
                        {{-- <li class=" {{ Request::is('/') ? 'active' : '' }}"><a href="{{ url('/') }}"> Kompletta Hjul </a></li>
                        <li class="{{ Request::is('falgar') ? 'active' : '' }}"><a href="{{ url('falgar') }}"> Fälgar </a></li>
                        <li class="dropdown dropdown-style">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Däck <b class="caret"> </b> </a>
                            <ul class="dropdown-menu col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl dropdown-nav">
                                <li class="{{ Request::is('sommardack') ? 'active' : '' }}"><a href="{{ url('sommardack') }}">Sommardäck</a></li>
                                <li class="{{ Request::is('friktionsdack') ? 'active' : '' }}"><a href="{{ url('friktionsdack') }}">Friktionsdäck</a></li>
                                <li class="{{ Request::is('dubbdack') ? 'active' : '' }}"><a href="{{ url('dubbdack') }}">Dubbdäck</a></li> --}}
                               <!-- <li class="megamenu-content ">
                                    <ul class="col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl">
                                        <li class="no-border">
                                            <p class="promo-1"><strong> Däck </strong></p>
                                        </li>
                                        <li><a href="category.html"> Sommardäck </a></li>
                                        <li><a href="category.html"> Friktionsdäck</a></li>
                                        <li><a href="category.html"> Dubbdäck </a></li>
                                        <li><a href="category.html"> MC Däck </a></li>
                                    </ul>
                                    <!--<ul class="col-lg-3  col-sm-3 col-md-3  col-xs-4">
                                        <li><a class="newProductMenuBlock" href="product-details.html"> <img
                                                class="img-responsive" src="images/site/promo1.jpg" alt="product"> <span
                                                class="ProductMenuCaption"> <i class="fa fa-caret-right"> </i> JEANS </span>
                                        </a></li>
                                    </ul>
                                    <ul class="col-lg-3  col-sm-3 col-md-3 col-xs-4">
                                        <li><a class="newProductMenuBlock" href="product-details.html"> <img
                                                class="img-responsive" src="images/site/promo2.jpg" alt="product"> <span
                                                class="ProductMenuCaption"> <i
                                                class="fa fa-caret-right"> </i> PARTY DRESS </span> </a></li>
                                    </ul>
                                    <ul class="col-lg-3  col-sm-3 col-md-3 col-xs-4">
                                        <li><a class="newProductMenuBlock" href="product-details.html"> <img
                                                class="img-responsive" src="images/site/promo3.jpg" alt="product"> <span
                                                class="ProductMenuCaption"> <i class="fa fa-caret-right"> </i> SHOES </span>
                                        </a></li>
                                    </ul>
                                </li>-->
                        {{--     </ul>
                        </li>

                        <!-- <li class="{{ Request::is('aterforsaljare') ? 'active' : '' }}"><a href="{{ url('aterforsaljare') }}"> Bli Återförsäljare </a></li>-->
                        <li class="{{ Request::is('tillbehor') ? 'active' : '' }}"><a href="{{ url('tillbehor') }}"> Tillbehör </a></li>
                        <li class="{{ Request::is('kontakt') ? 'active' : '' }}"><a href="{{ url('kontakt') }}"> Kontakta oss </a></li> --}}

                        <?php
                            $menu_list = array();
                            $dackSubMenuIndex = 0;

                            if(sizeOf(App\Menu::find(1)->pages) > 0 ){
                                foreach(App\Menu::find(1)->pages()->where('pages.is_active', 1)->orderBy('menu_page.priority')->limit(10)->get() as $page){
                                    // if($page->name=='sommardack' or $page->name=='friktionsdack' or $page->name=='dubbdack'){
                                    //     $dackSubMenuIndex = $dackSubMenuIndex ? $dackSubMenuIndex : $page->pivot->priority;
                                    //     $menu_list [$dackSubMenuIndex][] = $page;
                                    // }else{
                                        $menu_list [$page->pivot->priority] = $page;
                                    // }
                                }
                            }
                        ?>
                        @if($menu_list > 0 )
                            @foreach($menu_list as $page)
                                @if(sizeOf($page)>1)
                                <li class="dropdown dropdown-style">
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Däck <b class="caret"> </b> </a>
                                    <ul class="dropdown-menu col-lg-3  col-sm-3 col-md-3 unstyled noMarginLeft newCollectionUl dropdown-nav">
                                        @foreach($page as $spage)
                                            <li class="{{ Request::is($spage->name) ? 'active' : '' }}"><a href="{{ url($spage->name) }}"> {{$spage->label}} </a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                @else
                                <li class="{{ Request::is($page->name) ? 'active' : '' }}"><a href="{{ url($page->name) }}"> {{$page->label}} </a></li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                    <!--- this part will be hidden for mobile version -->
                </div>

            </div>
            <!--/.nav-collapse -->
        </div>
            

    </div>
    <!--/.container -->

    <div class="search-full text-right"><a class="pull-right search-close"> <i class=" fa fa-times-circle"> </i> </a>

        <div class="searchInputBox pull-right">
            <input id="textSearch" type="search" data-searchurl="search?=" name="q" placeholder="Sök här"
                   class="search-input">
            <button class="btn-nobg search-btn" type="submit"><i class="fa fa-search"> </i></button>
        </div>
    </div>
    <!--/.search-full-->

</div>
<!-- /.Fixed navbar  -->

    
<!-- **************************************************************-->
        @yield('content')

<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-6">
                    <h3> Support </h3>
                    <ul>
                        <li class="supportLi">
                            {{-- <h4><a class="inline" href="callto:+{{ App\Setting::getPhone() }}"> <strong> <i class="fa fa-phone"> </i> {{ App\Setting::getPhone() }} </strong> </a></h4> --}}
                            <h4><a class="inline" href="mailto:{{ App\Setting::getSupportMail() }}"> <i class="fa fa-envelope-o"> </i>
                                {{ App\Setting::getSupportMail() }}</a></h4>

                        </li>
                    </ul>

                </div>
                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-6">
                    <h3> Butik </h3>
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

                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-6">
                    <h3> Information </h3>
                    <ul>
{{--                         <li><a href="{{ url('faq')}}">FAQ?</a></li>
                        <li><a href="{{ url('kopvillkor') }}">Betal / Finans</a></li>
                        <li><a href="{{ url('villkor') }}"> Villkor</a></li>
 --}}                        {{-- <li><a href="{{ url('samarbetspartners') }}"> Samarbetspartners </a></li> --}}

                        @if(sizeOf(App\Menu::find(2)->pages) > 0 )
                            @foreach(App\Menu::find(2)->pages()->where('pages.is_active', 1)->orderBy('menu_page.priority')->take(6)->get() as $page)
                                <li class="{{ Request::is($page->name) ? 'active' : '' }}"><a href="{{ url($page->name) }}"> {{$page->label}} </a></li>
                            @endforeach
                        @endif

                    </ul>
                </div>
               {{--  <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Mitt konto </h3>
                    <ul>
                        <li><a href="{{ url('konto') }}"> Mitt Konto </a></li>
                        <li><a href="{{ url('adresser') }}"> Mina Adresser </a></li>
                        <li><a href="{{ url('orderlista') }}"> Order lista </a></li>
                        <li><a href="{{ url('konto_installningar') }}"> Konto inställningar </a></li>
                    </ul>
                </div> --}}

                {{-- <div style="clear:both" class="hide visible-xs"></div> --}}

                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-6">
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
                        @if(!empty(App\Setting::getFacebookLink()))
                            <li><a href="{{ App\Setting::getFacebookLink() }}"> <i class=" fa fa-facebook"> &nbsp; </i> </a></li>
                        @endif

                        @if(!empty(App\Setting::getInstagramLink()))
                            <li><a href="{{ App\Setting::getInstagramLink() }}"> <i class=" fa fa-instagram"> &nbsp; </i> </a></li>
                        @endif

                        @if(!empty(App\Setting::getTwitterLink()))
                            <li><a href="{{ App\Setting::getTwitterLink() }}"> <i class=" fa fa-twitter"> &nbsp; </i> </a></li>
                        @endif

                        @if(!empty(App\Setting::getYoutubeLink()))
                            <li><a href="{{ App\Setting::getYoutubeLink() }}"> <i class=" fa fa-youtube"> &nbsp; </i> </a></li>
                        @endif

                        @if(!empty(App\Setting::getGooglePlusLink()))
                            <li><a href="{{ App\Setting::getGooglePlusLink() }}"> <i class=" fa fa-google-plus"> &nbsp; </i> </a></li>
                        @endif
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

            <p class="pull-left"> &copy; Hjulonline {{ date('Y') }}. All rights reserved. </p>
        
            <div class="pull-right paymentMethodImg">
                
                <img height="30" class="pull-right" src="{{ asset('images/site/payment/master_card.png') }}" alt="master card"> 
                <img height="30" class="pull-right" src="{{ asset('images/site/payment/visa_card.png') }}" alt="visa card">
                {{-- <img height="30" class="pull-right" src="{{ asset('images/site/payment/SVEA_small.png') }}" alt="img"><br> --}}
                {{-- <img height="30" class="pull-right" src="{{ asset('images/site/payment/paypal.png') }}" alt="img">
                <img height="30" class="pull-right" src="{{ asset('images/site/payment/american_express_card.png') }}" alt="img"> 
                <img height="30" class="pull-right" src="{{ asset('images/site/payment/discover_network_card.png') }}" alt="img">
                <img height="30" class="pull-right" src="{{ asset('images/site/payment/google_wallet.png') }}" alt="img"> --}}
                
            </div>
        </div>
    </div>
    <!--/.footer-bottom-->
</footer>

<!-- Le javascript
================================================== -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('assets/js/pace.min.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/webshim/1.16.0/dev/polyfiller.js"></script>
<script src="{{ elixir('js/plugins.js') }}"></script>

<script> 

    @if(Session::has('productInCart'))
        toastr.success("{{ Session::get('productInCart') }}");
    @endif
    @if(Session::has('tokenMissMatch'))
        toastr.error("{{ Session::get('tokenMissMatch') }}");
    @endif

    $(document).on('keyup', '#productNameSearch', function(e) {
        // console.log('Hej! '+ $("#productNameSearch").val());
        if (e.keyCode === 27) {
            $('#suggestionBox').empty()
        } else {
            $.ajax({
                type: 'GET',
                url: '{{ url('productNameSearchSuggestion') }}',
                data: {
                    searchString: $("#productNameSearch").val()
                },
                success: function(data) {

                    $('#suggestionBox').empty();
                    
                    $('#suggestionBox').append(data.suggestionBox);
                }
            });
        }
    });

    $(document).click(function(e) {
        var target = e.target; //target div recorded
        if (jQuery(target).is('#suggestionBox *') || jQuery(target).is('#productNameSearch')) {
            //Do nothing
        } else {
            $('#suggestionBox').empty();
        }
    }); 

    $(document).on('click', '#search-all-btn', function() {
        $('#search-all-btn').attr('href', '');
        $('#search-all-btn').attr('href', "{{url('sok_sortimentet?soktxt=')}}" + $("#productNameSearch").val());
    });





    $(document).ready(function() {
        webshim.activeLang('sv');
        webshims.polyfill('forms');
        webshims.cfg.no$Switch = true;
        webshims.setOptions('loadStyles', true);
    })

    $(".getFullSearch").on('click', function (e) {
        $('.search-full').addClass("active"); //you can list several class names 
        e.preventDefault();
    });
    
    // function getBaseUrl() {
    //     var re = new RegExp(/^.*\//);
    //     return re.exec(window.location.href);
    // }

    // $(document).on('click', '#getCompanyInfo', function(e) {
    //     e.preventDefault();
    //     $.ajax({
    //         type: 'GET',
    //         url: '../../../getCompanyInfo',
    //         data: {
    //             orgNumber : $('#orgNumber').val(),
    //         },
    //         success: function(data) {
    //             // console.log(data.registerForm);
    //             $('#registerFormFields').empty();
    //             $('#registerFormFields').append(data.registerForm);
    //         }
    //     });
    // });

    // $(document).on('change', '.companyAddress', function(e) {
    //     e.preventDefault();
    //     $companyAddressId = $(this).val();
    //     // console.log($('#orgNumber').val(), $companyAddressId);

    //     $.ajax({
    //         type: 'GET',
    //         url: '../../../getCompanyAddress',
    //         data: {
    //             orgNumber : $('#orgNumber').val(),
    //             companyAddressId : $companyAddressId,
    //         },
    //         success: function(data) {
    //             // console.log(data.companyAddressInfo);
    //             $('#registerFormFields #billingStreetAddress').val(data.companyAddressInfo.address);
    //             $('#registerFormFields #billingPostalCode').val(data.companyAddressInfo.postalCode);
    //             $('#registerFormFields #billingCity').val(data.companyAddressInfo.city);
    //         }
    //     });
    // });

    // $(document).on('submit', '#signupFormModal', function(e) {
    //     e.preventDefault();
    //     $('#message').empty();

    //     $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //     });


    //     $.ajax({
    //         type: 'POST',
    //         url: '../../../registrera',
    //         data: $('#signupFormModal').serialize(),
    //         dataType: 'json',
    //         xhrFields: {
    //             withCredentials: true
    //         },
    //         success: function(data) {
    //             console.log(data.message);
    //             $('#message').append(data.message);
    //         }
    //     });
    // });

    $(document).ready(function() {
        $('#loading-image-register').hide();
    });

    $(document).ajaxStart(function(){
        $('#loading-image-register').show();
    }).ajaxStop(function(){
        $('#loading-image-register').hide();
    });

    // $(document).on('ifChecked', '#isRegisterShippingAddress', function() {
    //     $('.registerShippingAdressForm').removeClass('hidden');
    //     console.log(1);
    // });

    // $(document).on('ifUnchecked', '#isRegisterShippingAddress', function() {
    //     $('.registerShippingAdressForm').addClass('hidden');
    //     console.log(0);
    // });
    
    // $(document).on('change', '#isRegisterShippingAddress', function() {
    //     if($(this).prop("checked")) {
    //         $('.registerShippingAdressForm').removeClass('hidden');
    //     } else {
    //         $('.registerShippingAdressForm').addClass('hidden');
    //     }
    // });

</script>

<script type="application/ld+json"> 
    {
      "@context": "http://schema.org",
      "@type": "Organization",
      "url": "{{ url('') }}",
      "logo": "{{ asset('images/hjulonline_logo2.png') }}", 
      "contactPoint": [
        { "@type": "ContactPoint",
          "telephone": "{{ App\Setting::getPhone() }}",
          "contactType": "customer service"
        }
      ]
    }

</script>


<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Organization",
  "name": "Svenska Hjulonline",
  "url": "{{ url('') }}",
  "sameAs": [
        "{{ App\Setting::getFacebookLink() }}",
        "{{ App\Setting::getInstagramLink() }}",
        "{{ App\Setting::getTwitterLink() }}"
      ]
}

</script>

<script type="application/ld+json"> 
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "name": "{{ env('APP_NAME')}}",
      "url": "{{ url('') }}"
    }
</script>

{{-- <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script> --}}

<!-- include  parallax plugin -->
{{-- <script type="text/javascript" src="{{ asset('assets/js/jquery.parallax-1.1.js') }}"></script> --}}

<!-- optionally include helper plugins -->
{{-- <script type="text/javascript" src="{{ asset('assets/js/helper-plugins/jquery.mousewheel.min.js') }}"></script> --}}

<!-- include mCustomScrollbar plugin //Custom Scrollbar  -->
{{-- <script type="text/javascript" src="{{ asset('assets/js/jquery.mCustomScrollbar.js') }}"></script> --}}

<!-- include icheck plugin // customized checkboxes and radio buttons   -->
{{-- <script type="text/javascript" src="{{ asset('assets/plugins/icheck-1.x/icheck.min.js') }}"></script> --}}

<!-- include grid.js // for equal Div height  -->
{{-- <script src="{{ asset('assets/js/grids.js') }}"></script> --}}

<!-- include carousel slider plugin  -->
{{-- <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script> --}}

<!-- jQuery select2 // custom select   -->
{{-- <script src="http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script> --}}

<!-- include touchspin.js // touch friendly input spinner component   -->
{{-- <script src="{{ asset('assets/js/bootstrap.touchspin.js') }}"></script> --}}

<!-- include custom script for site  -->     
{{-- <script src="{{ asset('assets/js/script.js') }}"></script> --}}
<!-- **************************************************************-->

        @yield('footer')


</body>
</html>