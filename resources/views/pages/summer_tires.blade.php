@extends('layout')

@section('header')
      {{-- <link href="http://vjs.zencdn.net/5.10.4/video-js.css" rel="stylesheet"> --}}
    <!-- Custom styles for this template -->
    <link href="assets/css/home-v7.css" rel="stylesheet">
    {{-- <link rel="canonical" href="{{env('APP_URL')}}/sommardack"/> --}}
@endsection

@section('content')


<?php $slider = $page->sliders()->where('is_active', 1)->orderBy('priority')->first(); ?>
@if($slider)
    <style>
        .banner4 {
            background-image: url(" {{$slider->path }}");
        }
    </style>
    <div class="main-banner banner4 hidden-xs " aria-label="{{ $slider->title }}"></div>
@else
    <div class="main-banner hidden-xs "></div>
@endif

<div class="main-banner-mobile hidden-sm hidden-md hidden-lg"></div>

@include('pages.partials.car_search_box')

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

    <div class="row innerPage">
        {!! $page->content !!}
    </div>
    <!--/.innerPage-->
    <div style="clear:both"></div>
</div>
<!-- /.main-container -->

<div class="container main-container">
    <div id="searchContainer" class="row featuredPostContainer globalPadding style2">
        <div class="col-sm-12">
            @include('search.partials.search_result.front_page_products')
        </div>
    </div>
</div>

{{-- <div class="container main-container">

    <div class="row featuredPostContainer globalPadding style2">
        <h1 class="section-title style2 text-center"><span>POPULÄRA DÄCK</span></h1>
        @include('pages.partials.popular_products')
    </div>

</div> --}}


@endsection

@section('footer')

<!-- include jqueryCycle plugin -->
{{-- <script src="{{ asset('assets/js/jquery.cycle2.min.js') }}"></script> --}}

<!-- include easing plugin -->
{{-- <script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script> --}}

<script type="text/javascript">
    var productTypeID = {{$products->first()->product_type_id}};
</script>
{{-- <script src="{{ asset('assets/js/myCustomScripts/tires.js') }}"></script> --}}
<script src="{{ elixir('js/customScripts/tires.js') }}"></script>

@endsection
