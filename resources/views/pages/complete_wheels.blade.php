@extends('layout')

@section('header')
      {{-- <link href="http://vjs.zencdn.net/5.10.4/video-js.css" rel="stylesheet"> --}}
    <!-- Custom styles for this template -->
    <link href="assets/css/home-v7.css" rel="stylesheet">
    <meta name="google-site-verification" content="pABu7_ZfovT_TowhepND8nDv2xjYGoWKbFm1p-qhO2c" />
@endsection

@section('content')

<?php $slider = $page->sliders()->where('is_active', 1)->orderBy('priority')->first(); ?>
@if($slider)
    <style>
        .banner2 {
            background-image: url(" {{$slider->path }}");
        }
    </style>
    <div class="main-banner banner2 hidden-xs " aria-label="{{ $slider->title }}"></div>
@else
    <div class="main-banner hidden-xs "></div>
@endif

<div class="main-banner-mobile hidden-sm hidden-md hidden-lg"></div>

<div class="car-search-box" id="car-search-box">
    <div class="product-tab w100 clearfix">
        <ul id="search-tabs" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#search-reg-mobile" data-toggle="tab">SÖK PÅ REG.NR</a></li>
            <li><a href="#search-model-mobile" data-toggle="tab">SÖK PÅ BILMODELL</a></li>
        </ul>
    </div>
    <!--/.product-tab-->

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="full-width-div">
            <img id="loading-image" class="center-block" src="{{ asset('images/loading.svg') }}">
        </div>
        <div class="tab-pane active" id="search-reg-mobile">
        <form action="sok/reg/kompletta-hjul/falgar" method="POST">
            {{ csrf_field() }}
            @if (isset($searchData))
                @include('pages.partials.search_reg_form')
            @else
                <div class="inner-addon reg-search left-addon">
                    <i class="glyphicon" aria-hidden="true"><img height="70" src="assets/img/regsearch.png"></i>
                    <input id="regNrSearch" type="text" name="regnr" class="form-control" placeholder="ABC123" autocomplete="off" >
                </div><!-- /.inner-addon -->

                <div class="productFilter productFilterLook2" style="border:none">      
                    <ul id="complete_wheels_bullets" >
                        <li class="block-title-3 "><i class="fa fa-car"></i> Kompletta hjul </li><br>
                        <li class="block-title-3 "><i class="fa fa-tag"></i> Billiga priser</li><br>
                        <li class="block-title-3 "><i class="fa fa-check-square-o"></i> Snabba leveranser</li>
                    </ul>
                </div>
                <!-- productFilter -->
            @endif
   
        </form>
        </div>
        <div class="tab-pane" id="search-model-mobile"></div>
    </div>
    <!-- /.tab content -->
</div>
<!-- /.car-search-box -->
<br>

<div class="container">
    <div class="three-boxes">
        <div class="col-sm-4 ">
          <div class="panel-heading"><h2>Fälgar</h2></div>
          <div class="panel-body ">
                <div class="row row-centered">
                    <div class="block-explore col-centered ">
                        <div class="inner">
                            <a class="overly hw100" href="{{ url('falgar') }}">
                                {{-- <span class="explore-title"> Fälgar</span> --}}
                            </a>
                            <a href="{{ url('falgar') }}" class="img-block"> <img alt="img" src="images/site/frontpage-boxes/boxImg_12.jpg"
                                                                class="img-responsive"></a>
                        </div>
                    </div>
                </div>

          </div>
        </div>
        <div class="col-sm-4  ">
          <div class="panel-heading"><h2>Däck</h2></div>
          <div class="panel-body">
              <div class="row row-centered">
                    <div class="block-explore col-centered ">
                        <div class="inner">
                            <a class="overly hw100" href="{{ url('sommardack') }}">
                                {{-- <span class="explore-title"> Däck</span> --}}
                            </a>
                            <a href="{{ url('sommardack') }}" class="img-block"> <img alt="img" src="images/site/frontpage-boxes/boxImg_2.jpg"
                                                                class="img-responsive"></a>
                        </div>
                    </div>
                </div>
          </div>
        </div>
        <div class="col-sm-4  ">
          <div class="panel-heading"><h2>Tillbehör</h2></div>
          <div class="panel-body">
              <div class="row row-centered">
                    <div class="block-explore col-centered ">
                        <div class="inner">
                            <a class="overly hw100" href="{{ url('tillbehor') }}">
                                {{-- <span class="explore-title"> Tillbehör</span> --}}
                            </a>
                            <a href="{{ url('tillbehor') }}" class="img-block"> <img alt="img" src="images/site/frontpage-boxes/boxImg_3.jpg"
                                                                class="img-responsive"></a>
                        </div>
                    </div>
                </div>
          </div>
        </div>
    </div>
</div>

<br>


<div class="container">
    <div class="row">

                {{-- <h1 class="block-title-3" style="font-size: 1.6em;">Kompletta hjul, bildäck och fälg hos Hjul online</h1> --}}
                {{-- <p class="lead"> --}}
                    {!! $page->content !!}
                    {{-- Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. --}}<br><br>

                    {{--  Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. --}}

                {{-- </p> --}}


         {{--    <div class="col-sm-4">

                <h3 class="block-title-3" style="padding: 15px 0;" >Kontakta oss</h3>

                <p class="lead">
                    Du kan ringa oss mellan 08.00 till 12.00 vardagar eller maila oss på info@hjulonline.se dygnet runt.
                </p>
            </div> --}}
            
            <!-- <div class="row animated">
                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-6">
                    
                    <i class="glyphicon" aria-hidden="true"></i>
                    <span style="font-size:10em; margin:20px 30%;" class="glyphicon glyphicon-tags" aria-hidden="true"></span>

                    <h3 class="block-title-3 text-center">
                        PRISVÄRD
                    </h3>
                     <h3 class="text-center">
                        Exklusiva fälgar till förmånliga priser
                    </h3>

                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-6">
                    <i class="glyphicon" aria-hidden="true"></i>
                    <span style="font-size:10em; margin:20px 30%;" class="glyphicon glyphicon-time" aria-hidden="true"></span>

                    <h3 class="block-title-3 text-center">
                        Leverans
                    </h3>
                     <h3 class="text-center">
                        Snabb leverans till dig eller din verkstad                            
                    </h3>
                     

                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-6">
                    

                    <i class="glyphicon" aria-hidden="true"></i>
                    <span style="font-size:10em; margin:20px 30%;" class="glyphicon glyphicon-certificate" aria-hidden="true"></span>

                    <h3 class="block-title-3 text-center">
                        EXKLUSIVITET
                    </h3>
                     <h3 class="text-center">
                        Exklusivt & brett sortiment
                    </h3>

                </div>


            </div> -->
            <!--/.row-->
    </div>
    <!--/.innerPage-->
    <div style="clear:both"></div>
</div>
<!-- /.main-container -->


<div class="container" style="margin-top: 100px">

    <div class="width100 section-block">
        <h3 class="block-title-3 "><span> Några av de varumärken som vi jobbar med</span> <a id="nextBrand" class="link pull-right carousel-nav"> <i
                class="fa fa-angle-right"></i></a> <a id="prevBrand" class="link pull-right carousel-nav"> <i
                class="fa fa-angle-left"></i> </a></h3>

        <div class="row">
            <div class="col-lg-12">
                <ul class="no-margin brand-carousel owl-carousel owl-theme">
                    <li><a><img src="{{ asset("images/brand/achilles.png") }}" alt="achilles"></a></li>
                    <li><img src="{{ asset("images/brand/atlas.png") }}" alt="atlas"></li>
                    <li><img src="{{ asset("images/brand/autogrip.png") }}" alt="autogrip"></li>
                    <li><img src="{{ asset("images/brand/continental.png") }}" alt="continental"></li>
                    <li><img src="{{ asset("images/brand/dunlop.png") }}" alt="dunlop"></li>
                    <li><img src="{{ asset("images/brand/duro.png") }}" alt="duro"></li>
                    <li><img src="{{ asset("images/brand/goodyear.png") }}" alt="goodyear"></li>
                    <li><img src="{{ asset("images/brand/hankook.png") }}" alt="hankook"></li>
                    <li><img src="{{ asset("images/brand/michelin.png") }}" alt="michelin"></li>
                    <li><img src="{{ asset("images/brand/pirelli.png") }}" alt="pirelli"></li>
                </ul>
            </div>
        </div>
        <!--/.row-->
    </div>
    <!--/.section-block-->

</div>
<!--main-container-->
<br>

<style>
    .footer-top {
        background-color: #E3E3E3; 
        height: 65px; 
        padding-top: 20px; 
        padding-bottom: 15px; 
        color: #333; 
        border-bottom: 1px solid #ddd; 
    }

    .footer-top h3 {
        font-weight: 400;
        font-size: 1.5em;
    }

</style>

{{-- <div class="footer-top" >
    <div class="container">
        <div class="col-sm-4">
            <h3><i class="fa fa-truck" aria-hidden="true"></i> Fri frakt val</h3>
        </div>
        <div class="col-sm-4">
            <h3><i class="fa fa-tag" aria-hidden="true"></i>
Billiga priser</h3>
        </div>
        <div class="col-sm-4 text-center">
            <h3><i class="fa fa-check-square-o" aria-hidden="true"></i>
 Snabba leveranser</h3>
        </div>
    </div>
</div> --}}
<!-- Product Details Modal  -->
<!-- Modal -->
<div class="modal fade" id="productSetailsModalAjax" tabindex="-1" role="dialog"
     aria-labelledby="productSetailsModalAjaxLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- End Modal -->
@endsection

@section('footer')

<script>
    // this script required for subscribe modal
    $(window).load(function () {
        // full load
        $('#modalAds').modal('show');
        $('#modalAds').removeClass('hide');
    });
</script>

{{-- <script src="http://vjs.zencdn.net/5.10.4/video.js"></script> --}}

<script type="text/javascript">
    // $(document).ready(function() {
    //     // $('#my-video').remove();
    //     $('#slider-poster').hide();
    //     $('#my-video').delay(9000).fadeOut();
    //     setTimeout(function(){
    //         $('#my-video').remove();
    //         $('#slider-poster').fadeIn();
    //     }, 9000);
    // });

    // var video = videojs('my-video');  

    // video.ready(function() {
    //     this.play();
    //     setTimeout(() => {
    //         this.pause();
    //     }, 7000);
    // })                 
</script>

<!-- include jqueryCycle plugin -->
{{-- <script src="{{ asset('assets/js/jquery.cycle2.min.js') }}"></script> --}}

<!-- include easing plugin -->
{{-- <script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script> --}}

<!-- include custom script for only homepage  -->
{{-- <script src="{{ asset('assets/js/home.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/myCustomScripts/complete_wheels.js') }}"></script> --}}

<script src="{{ elixir('js/customScripts/complete_wheels.js') }}"></script>

@endsection