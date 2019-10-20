{{-- <div class="container headerOffset"> --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="w100 clearfix" style="border-bottom: 1px solid #fff; border-top: 1px solid #ddd; background: #fff; padding: 10px; margin-bottom: -30px;">
                <div class="col-sm-5 search-hit" > 
                    <div class="row row-eq-height"> 
                        @if (isset($product['searchData']))
                            <div class="col-sm-4"> 
                                <img height="100" src=" {{ $product['searchData']['ImageLink'] }}">
                            </div>

                            <div class="col-sm-8"> 
                                <a class="pull-right" style="padding-right: 10px;" href="{{ url('deleteSearchCookie?site='. $product['page']) }}">X</a> 
                                 <h3> {{ @$product['searchData']['CarSearchTitle'] }} </h3>
                                <p> 
                                    @if(!empty($product['searchData']['FoundDackBack']))
                                        Däck: {{ $product['searchData']['FoundDackBack'] }}<br> 
                                    @endif
                                    Fälg PCD: {{ $product['searchData']['PCD'] }} ET: {{ $product['searchData']['Offset']}}<br> 
                                    Nav: {{ $product['searchData']['ShowCenterBore'] }} {{ $product['searchData']['OE_Type'] }}<br> 
                                    Max fälgbredd F/B: {{ $product['searchData']['MaxRimWidthFront'] }}/{{ $product['searchData']['MaxRimWidthRear'] }}<br>

                                </p> 
                            </div> 
                        @else
                            Display dimensions and choices
                        @endif
                    </div> 
                </div>

                <div class="col-sm-7 searchDataBorder" style=""> 
                    
                            @if ($product['page'] == 'falgar')
                                <div class="row"> 
                                    <h3>Rekommenderade fälg dimensioner </h3>
                                </div>

                                <div class="row"> 
                                    <p>Klicka på önskad tum storlek</p>
                                    <div class="btn-group sizes">
                                    @foreach ($product['wheelSizes'] as $size)
                                        @if ($size['RimSize'] == $product['selectedSize'])
                                            <a href="falgar?size={{$size['RimSize']}}&page=1" class="btn btn-primary selectSizes active" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                        @else
                                            <a href="falgar?size={{$size['RimSize']}}&page=1" class="btn btn-primary selectSizes" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-3 no-padding">
                                        <select class="DDBrands form-control">
                                            <option value="">Välj märke</option>
                                            @foreach($product['brands'] as $brand)
                                                <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <style>
                                        .icheckbox_square-green {
                                            background-color: #fff
                                        }
                                    </style>

                                    <div class="col-sm-5 pull-right" style="margin-top: 5px; margin-bottom: 30px">
                                        <div class="check-btn">
                                            <label class="checkbox-inline"><input type="checkbox" name="directNav">Direktnav</label>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if (isset($product['wheelSizes']))
                                    <div class="row">
                                        <h3>Rekommenderade däck dimensioner </h3>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 no-padding">
                                            <p>Klicka på önskad tum storlek</p>
                                            
                                            <div class="btn-group sizes">
                                            @foreach ($product['wheelSizes'] as $size)
                                                @if ($size['RimSize'] == $product['selectedSize'])
                                                    <a href="sommardack?size={{$size['RimSize']}}&page=1" class="btn btn-primary selectSizes active" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                                @else
                                                    <a href="sommardack?size={{$size['RimSize']}}&page=1" class="btn btn-primary selectSizes" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                                @endif
                                            @endforeach
                                            </div>
                                        </div>

                                        <div class="col-sm-6 no-padding">
                                            <p>Rekommenderade däck dimensioner</p>

                                            <div class="btn-group dimensions">
                                            @foreach ($product['dimensions'] as $dimension)
                                                @if ($dimension == Request::input('dimension'))
                                                {{-- @if ($dimension == $product['selectedDimension'])) --}}
                                                    <a href="sommardack?dimensions={{$dimension}}&page=1" class="btn btn-primary selectDimensions active" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                                @elseif ($dimension == reset($product['dimensions']) && empty(Request::input('dimension')))
                                                {{-- @if ($dimension == $product['selectedDimension'])) --}}
                                                    <a href="sommardack?dimensions={{$dimension}}&page=1" class="btn btn-primary selectDimensions active" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                                @else
                                                    <a href="sommardack?dimensions={{$dimension}}&page=1" class="btn btn-primary selectDimensions" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                                @endif
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>


                                    <br>

                                    <div class="row">
                                        <div class="col-sm-3 no-padding">
                                            <select class="DDBrands form-control">
                                                <option value="">Välj märke</option>
                                                @foreach($product['brands'] as $brand)
                                                    <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                @else
                                    Display dimensions choises
                                @endif
                            @endif

                    </div> 
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}