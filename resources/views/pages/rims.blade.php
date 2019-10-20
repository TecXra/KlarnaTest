@extends('layout')

@section('header')
      {{-- <link href="http://vjs.zencdn.net/5.10.4/video-js.css" rel="stylesheet"> --}}
    <!-- Custom styles for this template -->
    <link href="assets/css/home-v7.css" rel="stylesheet">
    {{-- <link rel="canonical" href="{{env('APP_URL')}}/falgar"/> --}}
@endsection

@section('content')
 

<?php $slider = $page->sliders()->where('is_active', 1)->orderBy('priority')->first(); ?>
@if($slider)
    <style>
        .banner3 {
            background-image: url(" {{$slider->path }}");
        }
    </style>
    <div class="main-banner banner3 hidden-xs " aria-label="{{ $slider->title }}"></div>
@else
    <div class="main-banner hidden-xs "></div>
@endif

<div class="main-banner-mobile hidden-sm hidden-md hidden-lg"></div>

{{-- <div class="col-xs-12 col-sm-12 hidden-sm hidden-md hidden-lg" style=" margin: 0; margin-bottom: 20px; padding: 0; ">
    <img style="width: 100%; height: auto; margin: 0; margin-top: 8px; " src="{{ asset('images/slider/testSlider4-mini.jpg') }}">
</div> --}}
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
        <form action="sok/storlek/falgar" method="POST">
            {{csrf_field()}}
            <div class="inner-addon reg-search left-addon">
                <i class="glyphicon" aria-hidden="true"><img height="70" src="assets/img/regsearch.png"></i>
                <input id="regNrSearch" type="text" name="regnr" class="form-control" placeholder="ABC123" autocomplete="off" >
            </div><!-- /.inner-addon -->

            <div class="productFilter productFilterLook2">

    {{--             <div class="row">
                    <div class="col-sm-12">
                        <div class="radio product-label">
                            <label><input type="radio" name="optradio">Kompletta Hjul</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 padding-right-ajust">
                        <div class="radio product-label">
                            <label><input type="radio" name="optradio">Fälgar</label>
                        </div>
                    </div>
                    <div class="col-xs-6 padding-left-ajust">
                        <div class="radio product-label">
                            <label><input type="radio" name="optradio">Däck</label>
                        </div>
                    </div>    
                </div> --}}
                
                <div class="row">
                    <div class="col-xs-4 padding-right-ajust">
                        <div class="select-style">
                            <select id="product_inch" name="product_inch" class="form-control">
                                <option value="">Tum</option>
                                @foreach ($inches as $inch)
                                    <option value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust padding-right-ajust">
                        <div class="select-style">
                            <select id="product_width" name="product_width" class="form-control">
                                <option value="">Bredd</option>
                                @foreach ($widths as $width)
                                    <option value="{{$width->product_width}}">{{$width->product_width}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust">
                        <div class="select-style">
                            <select id="pcd" name="pcd" class="form-control">
                                <option value="">Bultmönster</option>
                                @foreach ($pcds as $pcd)
                                    <option value="{{$pcd}}">{{$pcd}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xs-6 padding-right-ajust">
                        <div class="select-style">
                            <select id="et" name="et" class="form-control">
                                <option value="">Inpressning</option>
                                @foreach ($ets as $et)
                                    <option value="{{$et->et}}">{{$et->et}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 padding-left-ajust">
                        <div class="select-style">
                            <select id="product_brand" name="product_brand" class="form-control">
                                <option value="">Märke</option>
                                @foreach ($brands as $brand)
                                    <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- productFilter -->

            {{-- <button class="btn" type="submit">
                Sök
                <i class="fa fa-long-arrow-right" > </i>
            </button> --}}
            <div class="cart-actions">
                 <button class="button btn-block btn-cart cart first" type="submit">Sök
                <i class="fa fa-long-arrow-right" > </i>
                </button>
            </div>
        </form>
        </div>
        
        <div class="tab-pane" id="search-model-mobile">
        </div>

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
        <h1 class="section-title style2 text-center"><span>POPULÄRA FÄLGAR
</span></h1>

        <div id="productslider" class="owl-carousel owl-theme">
            
            @foreach ($products as $product)
            <div class="item">
                <div class="product"> --}}
                    {{-- <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist"
                       data-placement="left">
                        <i class="glyphicon glyphicon-heart"></i>
                    </a> --}}

                    {{-- <div class="image"> --}}
                        <!-- <div class="quickview">
                            <a data-toggle="modal" class="btn btn-xs btn-quickview" href="ajax/product.html"
                               data-target="#productSetailsModalAjax">Quick View </a>
                        </div> -->
                      
                       {{--  <a href="{{ url($product->productUrl().'/pcd') }}">
                            <img height=170 src="{{ asset( $product->productImages->first()->thumbnail_path) }}" alt="img">
                        </a> --}}

                        {{-- <div class="promotion"><span class="new-product"> NYTT</span> <span
                                class="discount">15% RABATT</span></div> --}}
                    {{-- </div>
                    <div class="description">
                        <h4><a href="{{ url($product->productUrl().'/pcd') }}">{{ $product->product_name }} </a></h4>

                        @if ($product->quantity <= 0)
                            <span class="size" style="color:red;">
                                Slut på lager{{$product->main_supplier_id == 4 ? ', dock går att beställa.' : '.'}} <br> 
                                @if($product->available_at)
                                    Fylles på åter: {{ $product->available_at }}
                                @endif

                            </span>
                        @elseif($product->quantity < 4)
                            <span class="size" style="color:red;">
                                Endast {{ $product->quantity }} antal kvar. <br> 
                                @if($product->available_at)
                                    Fylles på åter: {{ $product->available_at }}
                                @endif
                            </span>
                        @endif --}}
                        
                        {{-- @if (!(empty($product->wet_grip) || empty($product->rolling_resistance) || empty($product->noise_emission_rating) || empty($product->noise_emission_decibel)))
                            <span class="size">
                                {{$product->wet_grip . '-' . $product->rolling_resistance . '-' . $product->noise_emission_rating . '-' . $product->noise_emission_decibel}}
                            </span>
                        @endif --}}

                   {{--  </div>
                    
                    @if(Auth::check())
                        <div class="price">
                            <span>{{ $product->webPrice() }} kr</span>
                        </div>
                    @endif

                    <div class="action-control">
                        <a class="btn btn-primary" href="{{ url($product->productUrl().'/pcd') }}">
                            <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Mer info </span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
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

<!-- include jqueryCycle plugin -->
{{-- <script src="{{ asset('assets/js/jquery.cycle2.min.js') }}"></script> --}}

<!-- include easing plugin -->
{{-- <script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script> --}}

{{-- <script src="{{ asset('assets/js/myCustomScripts/rims.js') }}"></script> --}}

<script src="{{ elixir('js/customScripts/rims.js') }}"></script>

@endsection
