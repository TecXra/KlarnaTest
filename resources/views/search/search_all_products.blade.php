@extends('layout')

@section('content')

<div class="container containerOffset" >
{{--     <div class="col-sm-5">
        
        <h2>FÄLG SÖKNING</h2>
        
        <div class="full-width-div">
            <img id="rim_loading-image" class="center-block" src="{{ asset('images/loading.svg') }}">
        </div>
        <form action="sok/storlek/falgar" method="POST">
            {{csrf_field()}}
            <div class="productFilter productFilterLook2">
                
                <div class="row">
                    <div class="col-xs-4 padding-right-ajust">
                        <div class="select-style">
                            <select id="rim_product_inch" name="product_inch" class="form-control">
                                <option value="">Tum</option>
                                @foreach ($rimInches as $inch)
                                    <option value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust padding-right-ajust">
                        <div class="select-style">
                            <select id="rim_product_width" name="product_width" class="form-control">
                                <option value="">Bredd</option>
                                @foreach ($rimWidths as $width)
                                    <option value="{{$width->product_width}}">{{$width->product_width}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust">
                        <div class="select-style">
                            <select id="rim_pcd" name="pcd" class="form-control">
                                <option value="">Bultmönster</option>
                                @foreach ($rimPcds as $pcd)
                                    <option value="{{$pcd}}">{{$pcd}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">

                    <div class="col-xs-6 padding-right-ajust">
                        <div class="select-style">
                            <select id="rim_et" name="et" class="form-control">
                                <option value="">Inpressning</option>
                                @foreach ($rimEts as $et)
                                    <option value="{{$et->et}}">{{$et->et}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 padding-left-ajust">
                        <div class="select-style">
                            <select id="rim_product_brand" name="product_brand" class="form-control">
                                <option value="">Märke</option>
                                @foreach ($rimBrands as $brand)
                                    <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart-actions">
                 <button class="button btn-block btn-cart cart first" type="submit">Sök
                <i class="fa fa-long-arrow-right" > </i>
                </button>
            </div>
        </form>
    </div>


    <div class="col-sm-5 col-sm-offset-2">

        <h2>DÄCK SÖKNING</h2>

        <div class="full-width-div">
            <img id="tire_loading-image" class="center-block" src="{{ asset('images/loading.svg') }}">
        </div>

        <form id="tireSearchForm" action="{{ url('sok/storlek/') }}" method="POST">
            {{csrf_field()}}

            <div class="productFilter productFilterLook2">

                <div class="row">
                    <div class="col-xs-4 padding-right-ajust">
                        <div class="select-style">
                            <select required id="tire_product_type" class="form-control">
                                <option value="">Däcktyp (Börja här)</option>
                                @foreach ($productType as $type)
                                    <option value="{{$type->product_type_id}}">{{$type->productType->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust padding-right-ajust">
                        <div class="select-style">
                            <select id="tire_product_width" name="product_width" class="form-control" disabled>
                                <option value="">Bredd</option>
                                @foreach ($tireWidths as $width)
                                    <option value="{{$width->product_width}}">{{$width->product_width}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust">
                        <div class="select-style">
                            <select id="tire_product_profile" name="product_profile" class="form-control" disabled>
                                <option value="">Profil</option>
                                @foreach ($tireProfiles as $profile)
                                    <option value="{{$profile->product_profile}}">{{$profile->product_profile}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-xs-4 padding-right-ajust">
                        <div class="select-style">
                            <select id="tire_product_inch" name="product_inch" class="form-control" disabled>
                                <option value="">Tum</option>
                                @foreach ($tireInches as $inch)
                                    <option value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust padding-right-ajust">
                        <div class="select-style">
                            <select id="tire_product_brand" name="product_brand" class="form-control" disabled>
                                <option value="">Alla märken</option>
                                @foreach ($tireBrands as $brand)
                                    <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust">
                        <div id="tire_product_model" class="select-style">
                            <select id="tireProductModel" name="product_model" class="form-control" disabled>
                                <option value="">Alla mönster</option>
                                 @foreach ($tireModels as $model)
                                    <option value="{{$model->product_model}}">{{$model->product_model}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-7 col-xs-offset-5">
                        <div class="check-btn">
                            <label class="checkbox-inline"><input type="checkbox" name="is_runflat" >Runflat</label>
                            <label class="checkbox-inline"><input type="checkbox" name="is_ctyre">C-Däck</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart-actions">
                 <button class="button btn-block btn-cart cart first" type="submit">Sök
                <i class="fa fa-long-arrow-right" > </i>
                </button>
            </div>
        </form>
        
    </div>
 --}}
     <div id="searchResult">
        {{-- @include('search.partials.search_result.search_result') --}}
        <div class="container main-container">
            <div id="searchContainer" class="row featuredPostContainer globalPadding style2">

                <div class="col-sm-12">

                    <div class="w100 productFilter clearfix">

                        <div class="pull-right ">

                            <div class="change-view pull-right"><a href="#" title="Grid" class="grid-view"> <i
                                    class="fa fa-th-large"></i> </a> <a href="#" title="List" class="list-view "><i
                                    class="fa fa-th-list"></i></a></div>
                        </div>
                    </div>
                    <!--/.productFilter-->
                    <div id="searchResult" class="row categoryProduct xsResponse clearfix">

                        @if (isset($product['status']->Status))
                            {{ $product['status']->Message }}
                        @else
                            @foreach ( $product['items']->chunk(6) as $set)
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
                                                        <img height=150 src="{{ asset( $item->productImages->first()->thumbnail_path)}}" alt="img">
                                                    @endif
                                                </a>
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
                                                    <div class="row">
                                                        <div class="col-xs-7 col-xs-offset-3">
                                                            <span class="rollingResistance pull-left">{{ $item->rolling_resistance }}</span>
                                                            <span class="wetGrip pull-left">{{ $item->wet_grip }}</span>
                                                            <span class="noiseEmissionDecibel pull-left">{{ $item->noise_emission_decibel }}</span>
                                                        </div>
                                                    </div>
                                                    <br>
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
                                                @endif

                                            </div>

                                            <div class="price"><span>{{ $item->webPrice() }} kr</span></div>
                                            <div class="action-control">

                                                <div{{--  class="col-sm-7 col-xs-12" --}}>
                                                    @if ($item->quantity < 4)
                                                        <?php $qty = $item->quantity; ?>
                                                    @else
                                                        <?php $qty = 4; ?>
                                                    @endif 


                                                    <form id="addToCart" action="{{ url('varukorg') }}" method="POST">
                                                        {!! csrf_field() !!}

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
                        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
                            {{-- <p>Visar 1–450 av 12 resultat</p> --}}
                        </div>
                    </div>
                    <!--/.categoryFooter-->
                </div>
                <!--/right column end-->    
            </div>
            <!--/.featuredPostContainer-->
        </div>
        <!-- /main container -->
    </div>
</div>
<!-- /. container -->
@endsection

@section('footer')

<script>

    function redirect(page) {
        var redirect = "";
        if(typeof page == "undefined" || page == null) {
            redirect = 'sok_sortimentet?soktxt='+$('#productNameSearch').val();
        } else {
            redirect = 'sok_sortimentet?soktxt='+$('#productNameSearch').val()+'&page=' + page;
        }

        if (history.pushState) {
          window.history.pushState("", "", redirect);
        } else {
          document.location.href = redirect;
        }
        return;
    }

   $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var page = $(this).text();
        redirect(page)
        // var page = $(this).text();
        
        $.ajax({
            type: 'GET',
            url: url, 
            data: {
                'soktxt' : $('#productNameSearch').val(),
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                $('#searchResult').empty();
                $('.categoryFooter').remove();
                $('#searchResult').append(data.searchResult);
                $('html, body').animate({scrollTop : 0},1000);
            }
        });
    });

    // $(document).on('change', '#rim_product_inch', function(){
    //     var url = 'filterRimsByInch';
    //     var callOnSuccess = showDimensionsByInch;
    //     var args = { 
    //         'pcd' : $('#pcd').val(),
    //         'product_inch' : $(this).val()
    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });

    // $(document).on('change', '#rim_product_width', function(){
    //     var url = 'filterRimsByWidth';
    //     var callOnSuccess = showDimensionsByWidth;
    //     var args = { 
    //         'pcd' : $('#rim_pcd').val(),
    //         'product_inch' : $('#rim_product_inch').val(),
    //         'product_width' : $(this).val()

    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });

    // $(document).on('change', '#rim_et', function(){
    //     var url = 'filterRimsByET';
    //     var callOnSuccess = showDimensionsByET;
    //     var args = { 
    //         'pcd' : $('#rim_pcd').val(),
    //         'product_inch' : $('#rim_product_inch').val(),
    //         'product_width' : $('#rim_product_width').val(),
    //         'et' : $(this).val()

    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });

     
    // function ajaxRequest(url, callOnSuccess, args) {
    //     if (args === undefined) {
    //         args = null;
    //     }
    //     $.ajax({
    //         type: 'get',
    //         url: url,
    //         data: args,
    //         dataType: 'json',
    //         xhrFields: {
    //             withCredentials: true
    //         },
    //         success: callOnSuccess
    //     });
    // }

    // var listSearchCars = function(data) {
    //     $('#search-model-mobile .list-group').remove();
    //     $('#search-model-mobile .inner-addon').remove();
    //     $('#search-model-mobile').append(data); 
    // }

    // var showDimensionsByInch = function(data) {
    //     console.log(data);
    //     $('#rim_product_width').html(data.html.dropdownWidth);
    //     $('#rim_et').html(data.html.dropdownET);
    //     $('#rim_product_brand').html(data.html.dropdownBrand);
    // };

    // var showDimensionsByWidth = function(data) {
    //     console.log(data);
    //     $('#rim_et').html(data.html.dropdownET);
    //     $('#rim_product_brand').html(data.html.dropdownBrand);
    // };

    // var showDimensionsByET = function(data) {
    //     console.log(data);
    //     $('#rim_product_brand').html(data.html.dropdownBrand);
    // };

    // $(document).ready(function() {
    //     $('#rim_loading-image').hide();
    // });

    // $(document).ajaxStart(function(){
    //     $('#rim_loading-image').show();
    // }).ajaxStop(function(){
    //     $('#rim_loading-image').hide();
    // });


    // //tire search
    // $(document).on('change', '#tire_product_type', function(){
    //     if($(this).val() >= 1 && $(this).val() <= 3 ) {
    //         $('#tire_product_width').prop('disabled', false);
    //         $('#tire_product_profile').prop('disabled', false);
    //         $('#tire_product_inch').prop('disabled', false);
    //         $('#tire_product_brand').prop('disabled', false);
    //         $('#tireProductModel').prop('disabled', false);
    //     } else {
    //         $('#tire_product_width').prop('disabled', true);
    //         $('#tire_product_profile').prop('disabled', true);
    //         $('#tire_product_inch').prop('disabled', true);
    //         $('#tire_product_brand').prop('disabled', true);
    //         $('#tireProductModel').prop('disabled', true);
    //     }

    //     $('#tireSearchForm').prop('action', "{{ url('sok/storlek') }}/" + $('#tire_product_type option:selected').text())
    // });

    // $(document).on('change', '#tire_product_width', function(){
    //     var url = 'filterByWidth/' + $('#tire_product_type').val();
    //     var callOnSuccess = showDimensionsByWidth;
    //     var args = { 
    //         'product_width' : $(this).val()
    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });

    // $(document).on('change', '#tire_product_profile', function(){
    //     var url = 'filterByProfile/' + $('#tire_product_type').val();
    //     var callOnSuccess = showDimensionsByProfile;
    //     var args = { 
    //         'product_width' : $('#product_width').val(),
    //         'product_profile' : $(this).val()
    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });

    // $(document).on('change', '#tire_product_inch', function(){
    //     var url = 'filterByInch/' + $('#tire_product_type').val();
    //     var callOnSuccess = showDimensionsByInch;
    //     var args = { 
    //         'product_width' : $('#product_width').val(),
    //         'product_profile' : $('#product_profile').val(),
    //         'product_inch' : $(this).val()
    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });

    // $(document).on('change', '#tire_brand', function(){
    //     var url = 'filterByBrand/' + $('#tire_product_type').val();
    //     var callOnSuccess = showDimensionsByBrand;
    //     var args = { 
    //         'product_width' : $('#product_width').val(),
    //         'product_profile' : $('#product_profile').val(),
    //         'product_inch' : $('#product_inch').val(),
    //         'product_brand' : $(this).val()
    //     };

    //     ajaxRequest(url, callOnSuccess, args);
    // });
     
    // function ajaxRequest(url, callOnSuccess, args) {
    //     if (args === undefined) {
    //         args = null;
    //     }
    //     $.ajax({
    //         type: 'get',
    //         url: url,
    //         data: args,
    //         dataType: 'json',
    //         xhrFields: {
    //             withCredentials: true
    //         },
    //         success: callOnSuccess
    //     });
    // }

    // var listSearchCars = function(data) {
    //     $('#search-model-mobile .list-group').remove();
    //     $('#search-model-mobile .inner-addon').remove();
    //     $('#search-model-mobile').append(data); 
    // };

    // var showDimensionsByWidth = function(data) {
    //     $('#tire_product_profile').html(data.html.dropdownProfile);
    //     $('#tire_product_inch').html(data.html.dropdownInch);
    //     $('#tire_product_brand').html(data.html.dropdownBrand);
    //     $('#tire_product_model select').remove();
    //     $('#tire_product_model').html(data.html.dropdownModel);
    // };

    // var showDimensionsByProfile = function(data) {
    //     $('#tire_product_inch').html(data.html.dropdownInch);
    //     $('#tire_product_brand').html(data.html.dropdownBrand);
    //     $('#tire_product_model select').remove();
    //     $('#tire_product_model').html(data.html.dropdownModel);
    // };

    // var showDimensionsByInch = function(data) {
    //     $('#brand').html(data.html.dropdownBrand);
    //     $('#tire_product_brand select').remove();
    //     $('#tire_product_model').html(data.html.dropdownModel);
    // };

    // var showDimensionsByBrand = function(data) {
    //     $('#tire_product_model select').remove();
    //     $('#tire_product_model').html(data.html.dropdownModel);
    // };

    // $(document).ready(function() {
    //     $('#tire_loading-image').hide();
    // });

    // $(document).ajaxStart(function(){
    //     $('#tire_loading-image').show();
    // }).ajaxStop(function(){
    //     $('#tire_loading-image').hide();
    // });
</script>
@endsection
