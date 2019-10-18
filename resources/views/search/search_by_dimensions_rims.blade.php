@extends('layout')

@section('content')

<div class="container containerOffset">
    <div class="row">
        <div class="col-sm-12">

            <form action="{{ url('sok/storlek/sommardack') }}" method="POST">
                {{csrf_field()}}

                {{-- <input type="hidden" name="productTypeID" value="1"> --}}
                
                <div id="searchByDimensions" class="productFilter productFilterLook2">
                    
                    <div class="row selectDimensionForm row-fluid">

                        <div class="col-xs-12 col-sm-2">
                            <div class="select-style">
                                <select id="inch" name="inch" class="form-control">
                                    <option value="">Tum</option>
                                    @foreach ($inches as $inch)
                                        @if( $inch->product_inch == $searchFilter['filterRimsByInch'])
                                            <option selected value="{{$inch->product_inch}}">{{$inch->product_inch}}</option> 
                                            <?php continue; ?>
                                        @else
                                            <option value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-2">
                            <div class="select-style">
                                <select id="width" name="width" class="form-control">
                                    <option value="">Bredd</option>
                                    @foreach ($widths as $width)
                                        @if( $width->product_width == $searchFilter['filterRimsByWidth'])
                                            <option selected value="{{$width->product_width}}">{{$width->product_width}}</option> 
                                            <?php continue; ?>
                                        @else
                                            <option value="{{$width->product_width}}">{{$width->product_width}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-2">
                            <div class="select-style">
                                <select id="et" name="et" class="form-control">
                                    <option value="" >Inpressning</option>
                                    @foreach ($ets as $et)
                                        @if( $et->et == $searchFilter['filterRimsByET'])
                                            <option selected value="{{$et->et}}">{{$et->et}}</option> 
                                            <?php continue; ?>
                                        @else
                                            <option value="{{$et->et}}">{{$et->et}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                
                        <div class="col-xs-12 col-sm-2">
                            <div class="select-style">
                                <select id="brand" name="brand" class="form-control">
                                    <option value="" >Märke</option>
                                    @foreach ($brands as $brand)
                                        @if( $brand->product_brand == $searchFilter['filterRimsByBrand'])
                                            <option selected value="{{$brand->product_brand}}">{{$brand->product_brand}}</option> 
                                            <?php continue; ?>
                                        @else
                                            <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-2">
                            <div class="select-style">
                                <select id="pcd" name="pcd" class="form-control">
                                    <option value="">Bultmönster</option>
                                    @foreach ($pcds as $pcd)
                                        @if( $pcd == $searchFilter['filterRimsByPCD'])
                                            <option selected value="{{$pcd}}">{{$pcd}}</option> 
                                            <?php continue; ?>
                                        @else
                                            <option value="{{$pcd}}">{{$pcd}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- {{ dd($inches, $widths, $ets, $brands, $pcds, $searchFilter) }} --}}
                    </div>
                </div>
                
                {{-- <div class="cart-actions">
                     <button class="button btn-block btn-cart cart first" type="submit">Sök
                    <i class="fa fa-long-arrow-right" > </i>
                    </button>
                </div> --}}
            </form>

        </div>
    </div>   
</div>

@include('search.partials.search_result.search_result', ['rim_dimension_search' => true])


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

{{-- <script src="{{ asset('assets/js/myCustomScripts/search_by_dimensions_rims.js') }}"></script> --}}

<script src="{{ elixir('js/customScripts/search_by_dimensions_rims.js') }}"></script>
{{-- <script src="../../../../dackline/{{ elixir('js/customScripts/search_by_dimensions_rims.js') }}"></script> --}}
<script>
    
    if (!!window.performance && window.performance.navigation.type === 2) {
        // value 2 means "The page was accessed by navigating into the history"
        console.log('Reloading');
        window.location.reload(); // reload whole page
    }
    // $(document).on('submit', '#addToCart', function(e) {
    //     e.preventDefault();

    //      $.ajax({
    //         type: 'POST',
    //         url: '../../varukorg', 
    //         data: $('#addToCart').serialize(),
    //         dataType: 'json',
    //         xhrFields: {
    //             withCredentials: true
    //         },
    //         success: function(data) {
    //             console.log('success');

    //         }
    //     });
    // });
</script>
@endsection