@extends('layout')

@section('header')
    <META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
@endsection

@section('content')
{{-- <div class="parallaxOffset no-padding fixedContent contact-intro">

    
</div> --}}
<!--/.contact-intro || map end-->

<div class="wrapper whitebg">
    <div class="container" style="margin-top:210px">
        <div class="row innerPage">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="row userInfo">

                    <div class="col-xs-12 col-sm-12">
                        {{-- <h3 class="section-title-style2 style2 text-left"><span>Tillbehör</span></h3> --}}
                        <h1 class="title-big section-title-style2 text-left" style="font-size: 3em">
                            <span> TPMS, däcktrycksövervakning, navringar och bultar! </span>
                        </h1>

                        {{-- <p class="lead ">
                            Vi på Streetwheels vill att du ska kunna göra precis så mycket du vill själv. Du som vet vad du behöver ska enkelt och bekvämt kunna beställa hem det. Därför hittar du här ett utbud av navringar, bultar, muttrar, samt TPMS.
                            <br>
                            <br>
                            Vet du inte vad du ska ha? Lugn, i kassan läggs automatiskt ett "monteringskit" till då du får rätt navringar och bult/mutter som passar din bil.
                        </p> --}}

                    </div>


                </div>
                <!--/.row  ||  -->


            </div>
        </div>
        <!--/row || innerPage end-->
        <div style="clear:both"></div>
    </div>
    <!-- ./main-container -->
    {{-- <div class="gap"></div> --}}
</div>
<!-- /main-container -->

<div class="container">

    <!-- Main component call to action -->
    <div class="col-sm-12">
        <div class="row featuredPostContainer globalPadding style2">

            {{-- <div id="productslider" class="owl-carousel owl-theme"> --}}
                <div class="row">
                    @foreach ($products as $product)
                   
                    <div class="col-sm-3 item">
                        <div class="product">
                            {{-- <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist"
                               data-placement="left">
                                <i class="glyphicon glyphicon-heart"></i>
                            </a> --}}

                            <div class="image">
                                <!-- <div class="quickview">
                                    <a data-toggle="modal" class="btn btn-xs btn-quickview" href="ajax/product.html"
                                       data-target="#productSetailsModalAjax">Quick View </a>
                                </div> -->
                              
                                <a href="{{ url($product->productType->name) }}">
                                    <img height=170 src="{{ url($product->productImages->first()->thumbnail_path) }}" alt="img">
                                </a>
                                
                            </div>
                            <div class="description">
                                <h4><a href="{{ url($product->productType->name) }}">{{ $product->product_code }} </a></h4>
                                
                               {{--  @if ($product->quantity <= 0)
                                    <span class="size" style="color:red;">
                                        Slut på lager
                                    </span>
                                @elseif($product->quantity < 4)
                                    <span class="size" style="color:red;">
                                        Endast {{ $product->quantity }} antal kvar
                                    </span>
                                @endif --}}

                            </div>
                            <div class="price"><span>{{ $product->webPrice() }} kr</span></div>
                            <div class="action-control">

                                <a class="btn btn-primary" href="{{ url($product->productType->name) }}"> 
                                    <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Mer info </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


            </div>
            <!--/.productslider-->

        </div>
        <!--/.featuredPostContainer-->
    </div>
</div>
<!-- /main container -->

@endsection


