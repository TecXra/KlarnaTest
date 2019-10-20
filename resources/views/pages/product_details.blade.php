@extends('layout')

@section('header')
    <!-- gall-item Gallery for gallery page -->
    <link href="{{ asset('assets/plugins/magnific/magnific-popup.css') }}" rel="stylesheet">
@endsection

<style>
    .tab-content a {
        color: #c0392b;
        text-decoration: underline;
    }

    .table tr:nth-child(1) > td {
        border: none !important;
    }

    .table > thead > tr > th {
        border: none !important;

    }
</style>

<?php
    $id="";
    if(!empty($product->first()->video_link)) {
        $url = $product->first()->video_link;
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
        $id = $matches[1];
    }
?>

<div class="modal fade" id="modal-video" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-video">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="video-frame" class="embed-responsive-item" type="text/html" 
                            src="https://www.youtube.com/embed/{{$id}}?rel=0&showinfo=0&color=white&iv_load_policy=3"
                            frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('content')
<!-- /.Fixed navbar  -->

<div class="container main-container containerOffset">
    <div id="productsection" class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="#">Lista</a></li>
                <li class="active">Produkt sida</li>
            </ul>
        </div>
    </div>
    

    @if (session()->has('success_message'))
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="row">
            <div class="alert alert-danger" style="margin-left:15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row transitionfx">

        <!-- left column -->

        <div class="col-lg-6 col-md-6 col-sm-6 productImageZoom">


            <div class='zoom' id='zoomContent'>

            @if(isset($product->first()->productImages->first()->path))
                <a class="gall-item" title="{{$product->first()->product_model}}" href="{{ asset( $product->first()->productImages->first()->path) }}">
                    <img style="width: 80%; height: auto"class="zoomImage1 img-responsive" data-src="{{ asset( $product->first()->productImages->first()->path) }}"
                    src="{{ asset( $product->first()->productImages->first()->path) }}" alt="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}"/>
                </a>
            @elseif ($product->first()->product_category_id == 1)
                <a class="gall-item" title="{{$product->first()->product_model}}" href="{{ asset( 'images/product/noTireImg.jpg' ) }}">
                    <img style="width: 80%; height: auto"class="zoomImage1 img-responsive" data-src="{{ asset('images/product/noTireImg.jpg') }}"
                    src="{{ asset( 'images/product/noTireImg.jpg') }}" alt="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}"/>
                </a>
            @elseif ($product->first()->product_category_id == 2)
                <a class="gall-item" title="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}" href="{{ asset( 'images/product/noRimImg.jpg') }}">
                    <img style="width: 80%; height: auto"class="zoomImage1 img-responsive" data-src="{{ asset('images/product/noRimImg.jpg') }}"
                    src="{{ asset('images/product/noRimImg.jpg') }}" alt="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}" />
                </a>
            @endif
            </div>


            <div class="zoomThumb ">
            @if(isset($product->first()->productImages->first()->path))
                @foreach($product->first()->productImages as $image)
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset($image->path) }}"
                         src="{{ asset($image->path) }}" alt="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}" alt="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}"/>
                    </a>
                @endforeach
            
                
                @if(!empty($product->first()->video_link))
                    <a href="#" data-toggle="modal" data-target="#modal-video" style="height: 119px; width: 119px; margin: 0; padding:0">
                    <img style="margin: 25% auto" width="70%" height="auto" src="{{ asset('images/site/YouTube-icon-full_color.png')}}" alt="{{ $product->first()->product_brand }} {{ $product->first()->product_model }}"/>
                    </a>
                @endif

            @endif

            </div>
            
        </div>
        <!--/ left column end -->


        <!-- right column -->
        <div class="col-lg-6 col-md-6 col-sm-5">
           <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-6 col-xxs-12 text-center-xs">
                        <h1 class="product-title"> {{$product->first()->product_name}}</h2>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-5 rightSidebar col-xs-6 col-xxs-12 text-center-xs hidden-xs" >
                    <h4  class="caps"><a style="font-size: 1.3em; color: #6FACEB" href="{{ app('url')->previous() }}"><i class="glyphicon glyphicon-share-alt icon-flipped"></i> Tillbaka </a></h4>
                </div>
            </div>

             <div class="product-price hidden-sm hidden-md hidden-lg">
                    <span class="price-sales"> {{ $product->first()->webPrice() }} kr</span>
                    <span class="price-standard">{{ $product->first()->originalPrice() > $product->first()->webPrice() ? $product->first()->originalPrice() . 'kr' : ''}} </span>
            </div>

            <div class="addto row hidden-sm hidden-md hidden-lg">

                <div class="col-sm-7 col-xs-12">
                    
                    {{-- <button onclick="productAddToCartForm.submit(this);"
                            class="button btn-block btn-cart cart first" title="Add to Cart" type="button">Lägg till varukorg
                    </button> --}}
                    @if ($product->first()->quantity < 4)
                        <?php $qty = $product->first()->quantity; ?>
                    @endif
                    @if ($product->first()->is_deleted != 1 and $product->first()->is_shown == 1)
                    <form action="{{ url('varukorg') }}" method="POST">
                        {!! csrf_field() !!}
                        @if ($product->first()->product_category_id == 2 && isset($rim_dimension_search))
                            <div class="select-style" style="width:210px; margin-bottom: 10px;">
                                <select id="pcd" name="pcd" class="form-control">
                                    @foreach ($product->first()->getPCDs() as $pcd)
                                        <option value="{{$pcd}}">{{$pcd}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <input type="hidden" name="id" value="{{ $product->first()->id }}">
                        <input type="hidden" name="name" value="{{ $product->first()->product_name }}">
                        <input type="hidden" name="price" value="{{ $product->first()->webPrice() }}">
                        <input type="text" name="quantity" value="{{ $qty }}" class="pull-left" style="width:40px; height:43px; margin-right: 10px; text-align: center;">
                        <input type="submit" class="btn btn-success btn-lg pull-left" value="Lägg till varukorg">
                    </form>
                    @endif
                </div>
                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><a class="link-wishlist wishlist btn-block ">Add
                    to Wishlist</a></div> -->
            </div>

            @if ($product->first()->rolling_resistance && $product->first()->wet_grip && $product->first()->noise_emission_decibel)
                {{-- <span class="size" style="border-top:1px solid #999; border-bottom:1px solid #999;padding: 1px" >
                    {{$product->first()->product_label}}
                </span> --}}
                <div class="row">
                    <div class="col-xs-7">

                        <span class="rollingResistance pull-left">{{ $product->first()->rolling_resistance }}</span>
                        <span class="wetGrip pull-left">{{ $product->first()->wet_grip }}</span>
                        <span class="noiseEmissionDecibel pull-left">{{ $product->first()->noise_emission_decibel }}</span>
                    </div>
                </div>
                <br>
            @endif



                {{-- <div class="rating">
                    <p><span><i class="fa fa-star"></i></span> <span><i class="fa fa-star"></i></span> <span><i
                            class="fa fa-star"></i></span> <span><i class="fa fa-star"></i></span> <span><i
                            class="fa fa-star-o "></i></span> <span class="ratingInfo"> <span> / </span> <a
                            data-toggle="modal" data-target="#modal-review"> Skriv en recension</a> </span></p>
                </div> --}}

               {{--  <div class="details-description">
                    <p>{{ $product->first()->product_description }} </p>
                </div> --}}

                <br>
                
                <div class="product-price hidden-xs">
                    <span class="price-sales"> {{ $product->first()->webPrice() }} kr</span>
                    <span class="price-standard">{{ $product->first()->originalPrice() > $product->first()->webPrice() ? $product->first()->originalPrice() . 'kr' : ''}} </span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-5 rightSidebar col-xs-6 col-xxs-12 text-center-xs hidden-sm hidden-md hidden-lg" >
                    <h4  class="caps"><a style="font-size: 1.3em; color: #6FACEB" href="{{ app('url')->previous() }}"><i class="glyphicon glyphicon-share-alt icon-flipped"></i> Tillbaka </a></h4>
                </div>


                <!-- <div class="color-details">
                    <span class="selected-color"><strong>COLOR</strong></span>
                    <ul class="swatches Color">
                        <li class="selected"><a style="background-color:#f1f40e"> </a></li>
                        <li><a style="background-color:#adadad"> </a></li>
                        <li><a style="background-color:#4EC67F"> </a></li>
                    </ul>
                </div -->
                <!--/.color-details-->

                {{-- <div class="productFilter productFilterLook2">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="filterBox">
                                <select class="form-control">
                                    <option value="" selected>Kvantitet</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
 --}}                <!-- productFilter -->
                
               
                <div class="cart-actions">

                    <div class="addto row hidden-xs">

                        <div class="col-sm-7 col-xs-12">
                            
                            {{-- <button onclick="productAddToCartForm.submit(this);"
                                    class="button btn-block btn-cart cart first" title="Add to Cart" type="button">Lägg till varukorg
                            </button> --}}
                            @if ($product->first()->quantity < 4)
                                <?php $qty = $product->first()->quantity; ?>
                            @endif 
                            <form action="{{ url('varukorg') }}" method="POST">
                                {!! csrf_field() !!}
                                @if ($product->first()->product_category_id == 2 && isset($rim_dimension_search))
                                    <div class="select-style" style="width:210px; margin-bottom: 10px;">
                                        <select id="pcd" name="pcd" class="form-control">
    {{--                                         <option value="">Bultmönster</option>
     --}}                                   @foreach ($product->first()->getPCDs() as $pcd)
                                                <option value="{{$pcd}}">{{$pcd}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <input type="hidden" name="id" value="{{ $product->first()->id }}">
                                <input type="hidden" name="name" value="{{ $product->first()->product_name }}">
                                <input type="hidden" name="price" value="{{ $product->first()->webPrice() }}">
                                <input type="text" name="quantity" value="{{ $qty }}" class="pull-left" style="width:40px; height:43px; margin-right: 10px; text-align: center;">
                                <input type="submit" class="btn btn-success btn-lg pull-left" value="Lägg till varukorg">
                            </form>
                        </div>
                        <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><a class="link-wishlist wishlist btn-block ">Add
                            to Wishlist</a></div> -->
                    </div>
                    <div class="row ">
                        <div class="col-sm-12">
                            {{-- <h3 classs="incaps"> Leveranstid: {{ $product->first()->delivery_time }}</h3> --}}
                            {{-- @if( $product->first()->product_category_id === 2 && strpos($product->first()->product_name, 'Blank') !== false) 
                                <h3 classs="incaps"> 7-14 dagars leveranstid</h3>
                            @elseif( $product->first()->product_category_id == 2)
                                <h3 classs="incaps"> 5-7 dagars leveranstid</h3>
                            @endif --}}
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col-sm-12">
                        {{-- <div style="clear:both"></div> --}}

                            @if ($product->first()->quantity >= 4)
                                <h3 class="incaps"><i class="fa fa fa-check-circle-o color-in"></i> Finns i lager</h3>
                            @elseif ($product->first()->quantity < 4 && $product->first()->quantity > 0)
                                <h3 class="incaps"> 
                                    <i class="fa fa-exclamation-circle" style="color:#CD4939;"></i> Endast {{ $qty = $product->first()->quantity }} kvar. 
                                    @if($product->first()->available_at)
                                        Fylles på åter: {{ $product->first()->available_at }}
                                    @endif
                                </h3>
                            @else 
                                <h3 class="incaps">
                                    <i class="fa fa-minus-circle color-out"></i> Slut på lager. 
                                    @if($product->first()->available_at)
                                        Tillgängligt åter: {{ $product->first()->available_at }}
                                    @endif
                                </h3>
                            @endif

                            <h3 class="incaps"><i class="glyphicon glyphicon-lock"></i> Säker online beställning</h3> 
                        </div>
                    </div>

                    @if(!empty($product->first()->tire_manufactor_date))
                         <div class="row">
                            <div class="col-sm-12">
                                <h5 classs="incaps"> Tillverkad: {{ $product->first()->tire_manufactor_date }}</h3>
                            </div>
                        </div>
                    @endif
                   
                    
                    
                    

                    {{-- <h3 class="incaps"><i class="glyphicon glyphicon-lock"></i> Säker online beställning</h3> --}}
                </div>
                <!--/.cart-actions-->

                <div class="clear"></div>

                <div class="product-tab w100 clearfix">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#productInformation" data-toggle="tab">Produktinformation</a></li>
                        <li><a href="#specification" data-toggle="tab">Specifikationer</a></li>
                        {{-- <li><a href="#size" data-toggle="tab">Storlekar</a></li>
                        <li><a href="#shipping" data-toggle="tab">Leverans</a></li> --}}
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="productInformation">
                            {{-- Köp dina {{ $product->first()->product_brand }} {{ $product->first()->product_model }} på wheelzone <br> --}}
                            @if($product->first()->product_category_id == 1)
                                <a href="{{ url('dack/'. str_slug($product->first()->product_brand))  }}">Se alla {{ $product->first()->product_brand }} däck</a>
                            @elseif($product->first()->product_category_id == 2)
                                <a href="{{ url('falg/'. str_slug($product->first()->product_brand))  }}">Se alla {{ $product->first()->product_brand }} fälgar</a>
                            @endif
                        </div>
                        <div class="tab-pane" id="specification"> 
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%"></th>
                                        <th style="width: 70%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Märke: </td>
                                        <td>{{ $product->first()->product_brand }}</td>
                                    </tr>

                                    <tr>
                                        <td>Modellnamn: </td>
                                        <td>{{ $product->first()->product_model }}</td>
                                    </tr>

                                    <tr>
                                        <td>Dimensioner: </td>
                                        <td>{{ $product->first()->product_dimension }}</td>
                                    </tr>

                                     @if($product->first()->product_category_id == 1)
                                        <tr>
                                            <td>Belastningsindex: </td>
                                            <td>{{ $product->first()->load_index  }}</td>
                                        </tr>

                                        <tr>
                                            <td>Hastighetsindex: </td>
                                            <td>{{ $product->first()->speed_index }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if($product->first()->product_category_id == 2)
                                        <tr>
                                            <td>Bultmönster: </td>
                                            <td>{{ implode(', ', $product->first()->getPCDs())  }}</td>
                                        </tr>

                                        <tr>
                                            <td>Inpressning: </td>
                                            <td>{{ $product->first()->et }}</td>
                                        </tr>

                                        <tr>
                                            <td>Navhål: </td>
                                            <td>{{ $product->first()->bore_max }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                                
                            </table>
                        </div>

                        {{--<div class="tab-pane" id="shipping">
                            Sed ut eros felis. Vestibulum rutrum imperdiet nunc a interdum. In scelerisque libero ut elit porttitor commodo. Suspendisse laoreet magna nec urna fringilla viverra
                        </div> --}}

                    </div>
                    <!-- /.tab content -->

                </div>
                <!--/.product-tab-->

                <div style="clear:both"></div>

                <div class="product-share clearfix">
                    <p> DELA MED DIG</p>

                    <div class="socialIcon">
                        @if(!empty(App\Setting::getFacebookLink()))
                            <a href="{{ App\Setting::getFacebookLink() }}"> <i class=" fa fa-facebook"> </i> </a>
                        @endif

                        @if(!empty(App\Setting::getInstagramLink()))
                            <a href="{{ App\Setting::getInstagramLink() }}"> <i class="fa fa-instagram"> </i> </a>
                        @endif

                        @if(!empty(App\Setting::getTwitterLink()))
                            <a href="{{ App\Setting::getTwitterLink() }}"> <i class="fa fa-twitter"> </i> </a> 
                        @endif

                        @if(!empty(App\Setting::getYoutubeLink()))
                            <a href="{{ App\Setting::getYoutubeLink() }}"> <i class="fa fa-youtube"> </i> </a> 
                        @endif

                        @if(!empty(App\Setting::getGooglePlusLink()))
                            <a href="{{ App\Setting::getGooglePlusLink() }}"> <i class="fa fa-google-plus"> </i> </a>
                        @endif
                    </div>

                </div>
                <!--/.product-share-->

        </div>
        <!--/ right column end -->

    </div>
    <!--/.row-->

    <div style="clear:both"></div>


</div>
<!-- /main-container -->


<div class="gap"></div>


<!-- Modal review start -->
<div class="modal  fade" id="modal-review" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h3 class="modal-title-site text-center">PRODUKT RECENSION </h3>
            </div>
            <div class="modal-body">

                <h3 class="reviewtitle uppercase">Du recenserar: Lorem ipsum dolor sit amet</h3>

                <form>
                    <div class="form-group">
                        <label>
                            Hur betygsätter du denna produkt? </label> <br>

                        <div class="rating-here">
                            <input type="hidden" class="rating-tooltip-manual" data-filled="fa fa-star fa-2x"
                                   data-empty="fa fa-star-o fa-2x" data-fractions="3"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rtext">Namn</label>
                        <input type="text" class="form-control" id="rtext" placeholder="Ditt Namn" required>
                    </div>

                    <div class="form-group ">
                        <label>Recension</label>
                        <textarea class="form-control" rows="3" placeholder="Din Recension" required></textarea>

                    </div>


                    <button type="submit" class="btn btn-success">Lämna in Recension</button>
                </form>


            </div>

        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal-dialog -->

</div>
<!-- /.Modal review -->

@endsection

@section('footer')

<!-- include jqueryCycle plugin -->
{{-- <script src="{{ asset('assets/js/jquery.cycle2.min.js') }}"></script> --}}

<!-- include easing plugin -->
{{-- <script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script> --}}

{{-- <script src="{{ asset('assets/js/jquery.zoom.js') }}"></script> --}}

<script>
    $(document).ready(function () {
        //$('.swipebox').zoom();

        // $('#zoomContent').zoom();
        $(".zoomThumb a").click(function () {
            var largeImage = $(this).find("img").attr('data-large');
            $(".zoomImage1").attr('src', largeImage);
            $(".zoomImg").attr('src', largeImage);
            $(".gall-item").attr('href', largeImage);

        });

        $('.productImageZoomx').magnificPopup({
            delegate: 'img', type: 'image', gallery: {enabled: true},

            callbacks: {
                elementParse: function () {
                    this.item.src = this.item.el.attr('src');
                }
            }

        });


        $('.gall-item').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        $("#zoomContent").click(function () {
            //alert();
            $('.gall-item').trigger('click');
        });

        // CHANGE IMAGE MODAL THUMB

        $(".product-thumbList a").click(function () {
            var largeImage = $(this).find("img").attr('data-large');
            $(".mainImg").attr('src', largeImage);

        });

        $("#modal-video").on("hidden.bs.modal", function () {
            $('#video-frame').attr('src', $('#video-frame').attr('src'));
        });

    });
</script>
<script src="{{ asset('assets/plugins/magnific/jquery.magnific-popup.min.js') }}"></script>

<script src="{{ asset('assets/plugins/rating/bootstrap-rating.min.js') }}"></script>


<script>
    $(function () {

        $('.rating-tooltip-manual').rating({
            extendSymbol: function () {
                var title;
                $(this).tooltip({
                    container: 'body',
                    placement: 'bottom',
                    trigger: 'manual',
                    title: function () {
                        return title;
                    }
                });
                $(this).on('rating.rateenter', function (e, rate) {
                    title = rate;
                    $(this).tooltip('show');
                })
                        .on('rating.rateleave', function () {
                            $(this).tooltip('hide');
                        });
            }
        });

    });
</script>
@endsection