@if($products)
    <div id="productslider" class="owl-carousel owl-theme">
    
         @foreach ($products as $product)
        <div class="item">
            <div class="product">
                {{-- <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist"
                   data-placement="left">
                    <i class="glyphicon glyphicon-heart"></i>
                </a> --}}
                <div class="image">
                    <a href="{{ url($product->productUrl()) }}">
                        @if(isset($product->productImages->first()->thumbnail_path))
                           <img height=170 src="{{ asset( $product->productImages->first()->thumbnail_path) }}" alt="img">
                        @endif
                    </a>
                </div>

               
                <div class="description">
                    <h4><a href="{{ url($product->productUrl()) }}">
                        {{ $product->product_name }} </a></h4>
                    @if (!empty($product->product_label))
                        <span class="size" style="border-top:1px solid #999; border-bottom:1px solid #999;padding: 1px" >
                            {{$product->product_label}}
                        </span>
                        <br>
                    @endif
                    
                    @if ($product->quantity <= 0)
                        <span class="size" style="color:red;">
                            Slut på lager. <br> 
                            @if($product->available_at)
                                Tillgängligt åter: {{ $product->available_at }}
                            @endif

                        </span>
                    @elseif($product->quantity < 4)
                        <span class="size" style="color:red;">
                            Endast {{ $product->quantity }} antal kvar. <br> 
                            @if($product->available_at)
                                Fylles på åter: {{ $product->available_at }}
                            @endif 
                        </span>
                    @endif
                    

                </div>
                <div class="price"><span>{{ $product->webPrice() }} kr</span></div>
                <div class="action-control">
                    <a class="btn btn-primary" href="{{ url($product->productUrl()) }}">
                        <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Mer info </span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        <!--
        <div class="item">
            <div class="product">
                <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist"
                   data-placement="left">
                    <i class="glyphicon glyphicon-heart"></i>
                </a>

                <div class="image">
                    <div class="quickview">
                        <a data-toggle="modal" class="btn btn-xs btn-quickview" href="ajax/product.html"
                           data-target="#productSetailsModalAjax">Quick View </a>
                    </div>
                    <a href="product-details.html"><img src="images/product/30.jpg" alt="img"
                                                        class="img-responsive"></a>

                    <div class="promotion"><span class="discount">15% OFF</span></div>
                </div>
                <div class="description">
                    <h4><a href="product-details.html">luptatum zzril delenit</a></h4>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                    <span class="size">XL / XXL / S </span></div>
                <div class="price"><span>$25</span></div>
                <div class="action-control"><a class="btn btn-primary"> <span class="add2cart"><i
                        class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
            </div>
        </div>-->

    </div>
    <!--/.productslider-->
@endif
