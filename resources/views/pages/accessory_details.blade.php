@extends('layout')

@section('header')
    <!-- gall-item Gallery for gallery page -->
    <link href="{{ asset('assets/plugins/magnific/magnific-popup.css') }}" rel="stylesheet">
@endsection


@section('content')
<!-- /.Fixed navbar  -->
<div class="container main-container containerOffset">
    <div class="row">
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

                {{-- @if (isset($product->productImages->first()->path)) --}}
                   <a class="gall-item" title="product-title" {{-- href="{{ asset( $product->productImages->first()->path . $product->productImages->first()->name) }}" --}}>
                    <img class="zoomImage1 img-responsive" data-src="{{ asset($product->productImages->first()->path) }}"
                        src="{{ asset($product->productImages->first()->path) }}"
                        alt='tpms'/>
                    </a>
{{--                 @elseif($product()->productType->name == 'falgar')
                    <a class="gall-item" title="product-title" href="{{ asset('images/product/noRimImg.jpg') }}">
                    <img class="zoomImage1 img-responsive" data-src="{{ asset('images/product/noRimImg.jpg') }}"
                        src="{{ asset('images/product/noRimImg.jpg') }}"
                        alt='Sommardäck'/>
                    </a>
                @else
                    <a class="gall-item" title="product-title" href="{{ asset('images/product/noImg.jpg') }}">
                    <img class="zoomImage1 img-responsive" data-src="{{ asset('images/product/noImg.jpg') }}"
                        src="{{ asset('images/product/noImg.jpg') }}"
                        alt='Sommardäck'/>
                    </a>
                @endif --}}
            </div>


            {{-- <div class="zoomThumb ">
                @if (isset($product()->productImages->first()->path))
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset($product()->productImages->first()->path) }}"
                         src="{{ asset($product()->productImages->first()->path) }}" alt="Saleen" title=""/>
                    </a>
                @elseif($product()->productType->name == 'falgar')
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset('images/product/noRimImg.jpg') }}"
                         src="{{ asset('images/product/noRimImg.jpg') }}" alt="Saleen" title=""/>
                    </a>
                @else
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset('images/product/noImg.jpg') }}"
                         src="{{ asset('images/product/noImg.jpg') }}" alt="Saleen" title=""/>
                    </a>
                @endif
                
                @if (isset($product()->productImages->first()->path))
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset($product()->productImages->first()->path) }}"
                         src="{{ asset($product()->productImages->first()->path) }}" alt="Saleen" title=""/>
                    </a>
                @elseif($product()->productType->name == 'falgar')
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset('images/product/noRimImg.jpg') }}"
                         src="{{ asset('images/product/noRimImg.jpg') }}" alt="Saleen" title=""/>
                    </a>
                @else
                    <a class="zoomThumbLink">
                    <img data-large="{{ asset('images/product/noImg.jpg') }}"
                         src="{{ asset('images/product/noImg.jpg') }}" alt="Saleen" title=""/>
                    </a>
                @endif

            </div> --}}

        </div>
        <!--/ left column end -->


        <!-- right column -->
        <div class="col-lg-6 col-md-6 col-sm-5">
           <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 col-xxs-12 text-center-xs">
                        <h1 class="product-title"> {{$product->product_name}}</h2>
                </div>
                {{-- <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar col-xs-6 col-xxs-12 text-center-xs">
                    <h4 class="caps"><a href="{{ url()->previous() }}"><i class="fa fa-chevron-left"></i> Tillbaka </a></h4>
                </div> --}}
            </div>


                {{-- <div class="rating">
                    <p><span><i class="fa fa-star"></i></span> <span><i class="fa fa-star"></i></span> <span><i
                            class="fa fa-star"></i></span> <span><i class="fa fa-star"></i></span> <span><i
                            class="fa fa-star-o "></i></span> <span class="ratingInfo"> <span> / </span> <a
                            data-toggle="modal" data-target="#modal-review"> Skriv en recension</a> </span></p>
                </div> --}}

               {{--  <div class="details-description">
                    <p>{{ $product()->product_description }} </p>
                </div> --}}

                <br>
                
                <div class="product-price">
                    <span class="price-sales"> {{ $product->webPrice() }} kr</span>
                    <span class="price-standard">{{ $product->originalPrice() > $product->webPrice() ? $product->originalPrice() . 'kr' : ''}} </span>
                </div>


               
                <div class="cart-actions">

                    <div class="addto row">

                        <div class="col-sm-7 col-xs-12">
                            
                            {{-- <button onclick="productAddToCartForm.submit(this);"
                                    class="button btn-block btn-cart cart first" title="Add to Cart" type="button">Lägg till varukorg
                            </button> --}}
                            @if ($product->quantity < 4)
                                <?php $qty = $product->quantity; ?>
                            @endif 
                            <form action="{{ url('varukorg') }}" method="POST">
                                {!! csrf_field() !!}
                                                {{-- {{dd($product->getNutsDimensions())}} --}}

                                @if ($product->product_type_id == 8)
                                    <div class="select-style" style="width:210px; margin-bottom: 10px;">
                                        <select id="nutDimension" name="nutDimension" class="form-control">
    {{--                                         <option value="">Bultmönster</option>--}}
                                            
                                            @foreach ($product->getNutsDimensions()  as $nut)
                                                @if($nut->id == $product->id)
                                                    <option selected value="{{$nut->id}}">{{$nut->product_dimension}}</option>
                                                @else
                                                    <option value="{{$nut->id}}">{{$nut->product_dimension}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($product->product_type_id == 9)
                                    <div class="select-style" style="width:210px; margin-bottom: 10px;">
                                        <select id="nutDimension" name="nutDimension" class="form-control">
    {{--                                         <option value="">Bultmönster</option>--}}
                                            
                                            @foreach ($product->getBoltsDimensions()  as $bolt)
                                                @if($bolt->id == $product->id)
                                                    <option selected value="{{$bolt->id}}">{{$bolt->product_dimension}}</option>
                                                @else
                                                    <option value="{{$bolt->id}}">{{$bolt->product_dimension}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($product->product_type_id == 13)
                                    <div class="select-style" style="width:210px; margin-bottom: 10px;">
                                        <select id="boltDimension" name="boltDimension" class="form-control">
    {{--                                         <option value="">Bultmönster</option>--}}
                                        
                                            @foreach ($product->getSpacersDimensions() as $spacer)
                                                 @if($spacer->id == $product->id)
                                                    <option selected value="{{$spacer->id}}">{{$spacer->product_dimension}}</option>
                                                @else
                                                    <option value="{{$spacer->id}}">{{$spacer->product_dimension}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($product->product_type_id == 10)
                                    <div class="clearfix">
                                        <div class="pull-left">
                                            <span>Mått: </span>
                                            <div class="select-style" style="width:210px; margin-bottom: 10px;margin-right: 10px;">
                                               
                                                <select id="outerDimension" name="outerDimension" class="form-control">
            {{--                                         <option value="">Bultmönster</option>--}}
                                                    @foreach ($product->getRingsOuterDimensions() as $outerD)
                                                        @if($outerD->product_dimension == $product->product_dimension)
                                                             <option selected value="{{$outerD->product_dimension}}">{{$outerD->product_dimension}}</option>
                                                        @else
                                                             <option value="{{$outerD->product_dimension}}">{{$outerD->product_dimension}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div> 
                                        </div>
                                    </div>
                                @endif    

                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->product_name }}">
                                <input type="hidden" name="price" value="{{ $product->webPrice() }}">
                                <input type="text" name="quantity" value="{{ $qty }}" class="pull-left" style="width:40px; height:43px; margin-right: 10px; text-align: center;">
                                <input type="submit" class="btn btn-success btn-lg pull-left" value="Lägg till varukorg">
                            </form>
                        </div>
                        <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><a class="link-wishlist wishlist btn-block ">Add
                            to Wishlist</a></div> -->
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @if( $product->product_category_id === 2 && strpos($product->product_name, 'Blank') !== false) 
                                <h3 classs="incaps"> 7-14 dagars leveranstid</h3>
                            @elseif( $product->product_category_id == 2)
                                <h3 classs="incaps"> 5-7 dagars leveranstid</h3>
                            @endif
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col-sm-12">
                        {{-- <div style="clear:both"></div> --}}

                            @if ($product->quantity >= 4)
                                <h3 class="incaps"><i class="fa fa fa-check-circle-o color-in"></i> Finns i lager</h3>
                            @elseif ($product->quantity < 4 && $product->quantity > 0)
                                <h3 class="incaps"> 
                                    <i class="fa fa-exclamation-circle" style="color:#CD4939;"></i> Endast {{ $qty = $product->quantity }} kvar
                                </h3>
                            @else 
                                <h3 class="incaps"><i class="fa fa-minus-circle color-out"></i> Slut på lager
                            @endif

                            <h3 class="incaps"><i class="glyphicon glyphicon-lock"></i> Säker online beställning</h3> 
                        </div>
                    </div>
                    
                    
                    

                    {{-- <h3 class="incaps"><i class="glyphicon glyphicon-lock"></i> Säker online beställning</h3> --}}
                </div>
                <!--/.cart-actions-->

                <div class="clear"></div>

                <div class="product-tab w100 clearfix">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#details" data-toggle="tab">Produktinformation</a></li>
                        {{-- <li><a href="#size" data-toggle="tab">Storlekar</a></li>
                        <li><a href="#shipping" data-toggle="tab">Leverans</a></li> --}}
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="details">
                            {{ $product->product_description }} <br>
                            
                        <br>
                        </div>
                        {{-- <div class="tab-pane" id="size"> 
                            Sed ut eros felis. Vestibulum rutrum imperdiet nunc a interdum. In scelerisque libero ut elit porttitor commodo. Suspendisse laoreet magna nec urna fringilla viverra<br>
                            Sed ut eros felis. Vestibulum rutrum imperdiet nunc a interdum. In scelerisque libero ut elit porttitor commodo. Suspendisse laoreet magna nec urna fringilla viverra
                        </div>

                        <div class="tab-pane" id="shipping">
                            Sed ut eros felis. Vestibulum rutrum imperdiet nunc a interdum. In scelerisque libero ut elit porttitor commodo. Suspendisse laoreet magna nec urna fringilla viverra
                        </div> --}}

                    </div>
                    <!-- /.tab content -->

                </div>
                <!--/.product-tab-->

                <div style="clear:both"></div>

                <div class="product-share clearfix">
                    <p> DELA </p>

                    <div class="socialIcon">
                        <a href="#"> <i class="fa fa-facebook"></i></a>
                        <a href="#"> <i class="fa fa-twitter"></i></a>
                        <a href="#"> <i class="fa fa-google-plus"></i></a>
                        <a href="#"> <i class="fa fa-pinterest"></i></a></div>
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

<script src="{{ asset('assets/js/jquery.zoom.js') }}"></script>

<script>
    $(document).ready(function () {
        //$('.swipebox').zoom();

        $('#zoomContent').zoom();
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

{{-- <script src="{{ asset('assets/js/myCustomScripts/accessory_details.js') }}"></script> --}}
<script src="{{ elixir('js/customScripts/accessory_details.js') }}"></script>
{{-- <script src="../dackline/{{ elixir('js/customScripts/accessory_details.js') }}"></script> --}}

@endsection