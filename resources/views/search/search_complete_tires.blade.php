@extends('layout')

@section('content')

<div class="container containerOffset">
    <div class="row">
        <div class="col-sm-12">
            <div class="w100 clearfix">
                <ul class="orderStep orderStepLook2">
                    <li ><a href="{{url('')}}"> <i class="fa fa-car "></i> <span> Välj bil</span>
                    </a></li>
                    <li><a href="{{ url('sok/reg/kompletta-hjul/falgar') }}"> <i class="fa fa-asterisk  "></i>
                        <span> Välj fälg </span></a></li>
                    <li class="active"><a href="{{ url('sok/reg/kompletta-hjul/dack') }}"><i class="fa fa-circle-o "> </i><span>Välj däck</span> </a></li>
                    <li><span class="disable"><i class="fa fa-thumbs-up  "> </i><span>Klart</span> </span></li>
                </ul>
                <!--/.orderStep end-->
            </div>
        </div>
    </div>
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
                                <a class="pull-right" href="{{ url('deleteSearchCookie?site='. $product['page']) }}">X</a> 
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

                {{-- <div class="col-sm-7" style="border-left: 3px solid #fff; padding-left:30px"> 
                    <div class="row"> 
                        <div class="col-sm-12"> 

                            @if (isset($product['wheelSizes']))
                                <h3>Rekommenderade fälg dimensioner </h3>
                                <p>Klicka på önskad tum storlek</p>
                                
                                <div class="btn-group sizes">
                                @foreach ($product['wheelSizes'] as $size)
                                    @if ($size['RimSize'] == $product['selectedSize'])
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectSizes active" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                    @else
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectSizes" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                    @endif
                                @endforeach
                                </div>

                                <br>
                                <br>
                                <p>Rekommenderade däck dimensioner</p>

                                <div class="btn-group dimensions">
                                @foreach ($product['dimensions'] as $dimension)
                                    @if ($dimension == reset($product['dimensions']))
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions active" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                    @else
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                    @endif
                                @endforeach
                                </div>

                            @else
                                Display dimensions choises
                            @endif

                        </div> 
                    </div> 
                </div> --}}
                <div class="col-sm-7 searchDataBorder" style=""> 
                    
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
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectSizes active" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                    @else
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectSizes" role="button" data-size="{{$size['RimSize']}}">{{$size['RimSize']}}</a>
                                    @endif
                                @endforeach
                                </div>
                            </div>

                            <div class="col-sm-6 no-padding">
                                <p>Rekommenderade däck dimensioner</p>

                                <div class="btn-group dimensions">
                                @foreach ($product['dimensions'] as $dimension)
                                    @if ($dimension == Request::input('dimension'))
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions active" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                    @elseif ($dimension == reset($product['dimensions']) && empty(Request::input('dimension')))
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions active" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
                                    @else
                                        <a href="{{ url('sok/reg/kompletta-hjul/dack') }}" class="btn btn-primary selectDimensions" role="button" data-dimension="{{$dimension}}">{{$dimension}}</a>
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

                </div>
            </div>
        </div>
    </div>
    {{-- @include('search.partials.search_data.search_data') --}}
</div>

@include('search.partials.search_result.search_result')


{{-- <div class="parallax-section parallax-image-3">
    <div class="w100 parallax-section-overley">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="parallax-content clearfix">
                        <h1 class="xlarge">   Vi erbjuder alltid fri frakt vid kompletta hjul </h1>
                        <h5 class="parallaxSubtitle"> Vi erbjuder alltid fri frakt vid beställning av kompletta hjul  </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!--/.parallax-section-->

<!-- Product Details Modal  -->
<!-- Modal -->
{{-- <div class="modal fade" id="productSetailsModalAjax" tabindex="-1" role="dialog"
     aria-labelledby="productSetailsModalAjaxLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div> --}}
<!-- /.modal -->
<!-- End Modal -->

@endsection

@section('footer')
<!-- include jqueryCycle plugin -->
{{-- <script src="{{ asset('assets/js/jquery.cycle2.min.js') }}"></script> --}}

<script type="text/javascript">
    var productTypeID = {{$product['productTypeID']}};
</script>
{{-- <script src="{{ asset('assets/js/myCustomScripts/search_complete_tires.js') }}"></script> --}}
<script src="{{ elixir('js/customScripts/search_complete_tires.js') }}"></script>
{{-- <script src="../../../../dackline/{{ elixir('js/customScripts/search_complete_tires.js') }}"></script> --}}


@endsection