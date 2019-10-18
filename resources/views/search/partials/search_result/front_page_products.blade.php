
<div id="searchResult" class="row categoryProduct xsResponse clearfix">
    <section class="endless-pagination " data-next-page="{{ $products->nextPageUrl() }}">
    @if (sizeOf($products) <= 0)
        Inga produkter
    @else
        @foreach ( $products->chunk(6) as $set)
            <div class="row">

            @foreach ($set as $item)
                <div class="item col-xs-6 col-sm-4 col-md-2">
                    <div class="product">
                        <div class="image">
                            @if($item->product_category_id == 2)
                                <!-- <a href="{{ url($item->productType->name . '/pcd/'. $item->id) }}"> -->
                               <a href="{{ url($item->productUrl().'/pcd') }}">
                            @else
                                <!-- <a href="{{ url($item->productType->name .'/'. $item->id) }}"> -->
                                <a href="{{ url($item->productUrl()) }}">
                            @endif
                                @if(isset($item->productImages->first()->thumbnail_path))
                                    <img height=150 src="{{ asset( $item->productImages->first()->thumbnail_path)}}" alt="{{ $item->product_brand }} {{ $item->product_model }}">
                                @endif
                            </a>

                            {{-- <div class="promotion"><span class="new-product"> NYTT</span> <span class="discount">Fri Frakt</span></div> --}}
                        </div>

                        <div class="description">
                            @if($item->product_category_id == 2)
                                <h4>
                                    <!-- <a href="{{ url($item->productType->name . '/pcd/'. $item->id) }}">{{ $item->product_name }} </a> -->
                                    <a href="{{ url($item->productUrl().'/pcd') }}">{{ $item->product_name }} </a>

                                </h4>
                            @else
                                <h4>
                                    <!-- <a href="{{ url($item->productType->name . '/'. $item->id) }}">{{ $item->product_name }} </a> -->
                                   <a href="{{ url($item->productUrl()) }}">{{ $item->product_name }} </a>
                                </h4>
                            @endif

                            <br>                                    
                            @if ($item->rolling_resistance && $item->wet_grip && $item->noise_emission_decibel)
                                {{-- <span class="size" style="border-top:1px solid #999; border-bottom:1px solid #999;padding: 1px" >
                                    {{$item->product_label}}
                                </span> --}}
                                <div class="row">
                                    <div class="col-xs-7 col-xs-offset-3 col-md-8 col-md-offset-2">
                                        <span class="rollingResistance pull-left">{{ $item->rolling_resistance }}</span>
                                        <span class="wetGrip pull-left">{{ $item->wet_grip }}</span>
                                        <span class="noiseEmissionDecibel pull-left">{{ $item->noise_emission_decibel }}</span>
                                    </div>
                                </div>
                                <br>
                            @else
                                <div style="margin-bottom: 58px"></div>
                            @endif

                            @if ($item->quantity <= 0)
                                <span class="size" style="color:red;">
                                    Slut på lager{{$item->main_supplier_id == 4 ? ', dock går att beställa.' : '.'}} <br> 
                                    @if($item->available_at)
                                        Fylles på åter: {{ $item->available_at }}
                                    @endif

                                </span>
                            @elseif($item->quantity < 4)
                                <span class="size" style="color:red;">
                                    Endast {{ $item->quantity }} antal kvar. <br> 
                                    @if($item->available_at)
                                        Fylles på åter: {{ $item->available_at }}
                                    @endif
                                </span>
                            @else
                                <span class="size" style="color:red;">
                                    <br>
                                </span>
                            @endif

                        </div>

                        <div class="price"><span>{{ $item->webPrice() }} kr</span></div>
                        <div class="action-control">

                            <div{{--  class="col-sm-7 col-xs-12" --}}>
                                
                                {{-- <button onclick="productAddToCartForm.submit(this);"
                                        class="button btn-block btn-cart cart first" title="Add to Cart" type="button">Lägg till varukorg
                                </button> --}}
                                @if ($item->quantity < 4)
                                    <?php $qty = $item->quantity; ?>
                                @else
                                    <?php $qty = 4; ?>
                                @endif 


                                <form id="addToCart" action="{{ url('varukorg') }}" method="POST">
                                    {!! csrf_field() !!}
                                    {{-- @if($item->product_category_id == 2 && isset($rim_dimension_search))
                                        <div class="select-style" style="width:120px; margin: 10px auto;">
                                            <select id="pcd1" name="pcd1" class="form-control">
                                           @foreach ($item->getPCDs() as $pcd)
                                                    <option value="{{$pcd}}">{{$pcd}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif --}}

                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="name" value="{{ $item->product_name }}">
                                    <input type="hidden" name="price" value="{{ $item->webPrice() }}">
                                    <input type="text" name="quantity" value="{{ $qty }}" style="width:37px; height:36px; padding-bottom: 3.5px; text-align: center;">
                                    <button type="submit" class="btn btn-primary"> 
                                        <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Köp </span>
                                    </button>
                                    {{-- <input type="submit" class="btn btn-success btn-lg pull-left" value="Köp"> --}}
                                </form>

                            </div>
                            @if (isset($rim_dimension_search))
                                {{-- <div class="select-style" style="width:106px; margin: 5px auto">
                                    <select id="pcd1" name="pcd1" class="form-control">
                                       @foreach ($item->getPCDs() as $pcd)
                                            <option value="{{$pcd}}">{{$pcd}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <!-- <a href="{{ url($item->productType->name . '/pcd/'. $item->id) }}"> 
                                    <span class="add2cart"> <i class="glyphicon glyphicon-shopping-cart"> </i> Mer info </span>
                                </a> -->
                                <a href="{{ url($item->productUrl().'/pcd') }}">
                                    <span class="add2cart"></i> Mer info </span>
                                </a>
                            @else
                                <!-- <a href="{{ url($item->productType->name . '/' . $item->id) }}"> 
                                    <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Mer info </span>
                                </a> -->
                                <a href="{{ url($item->productUrl()) }}">
                                    <span class="add2cart"></i> Mer info </span>
                                </a>
                            @endif
                            
                        </div>
                    </div>
                </div>
            @endforeach

            </div>
        @endforeach
    @endif
    </section>

</div>
<!--/.categoryProduct || product content end-->

<div class="w100 categoryFooter">
    {{-- AJAX Handel. PreventDefault and send AJAX request to ?page=1 --}}
    <span id="pagination">{!! $products->appends(Request::only('size'))->render() !!}</span>
    <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        {{-- <p>Visar 1–450 av 12 resultat</p> --}}
    </div>
</div>
<!--/.categoryFooter-->
