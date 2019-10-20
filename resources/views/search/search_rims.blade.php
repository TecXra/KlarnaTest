@extends('layout')

@section('content')

<div class="container containerOffset">
    @include('search.partials.search_data.search_data')
</div>

<div class="container main-container">

    <!-- Main component call to action -->

    <div id="searchContainer" class="row featuredPostContainer globalPadding style2">
        {{-- <h3 class="section-title style2 text-center"><span>DIN SÖKNING</span></h3> --}}
        <!--right column-->
        <div class="col-sm-12">
            <!-- <div class="w100 clearfix category-top">
                <h2> MEN COLLECTION </h2>

                <div class="categoryImage"><img src="images/site/category.jpg" class="img-responsive" alt="img"></div>
            </div> -->
            <!--/.category-top-->

            <div class="w100 productFilter clearfix">
                <p class="pull-left"> Visar <strong id="productCount">{{ $product['items']->total() }}</strong> produkter </p>

                <div class="pull-right ">
                    <div class="change-order pull-right">
                       {{--  <select class="form-control" name="orderby">
                            <option selected="selected">Default sortering</option>
                            <option value="popularity">Sortera efter popularitet</option>
                            <option value="rating">Sortera efter betyg</option>
                            <option value="date">Sortera efter nyast</option>
                            <option value="price">Sortera efter pris: lågt till högt</option>
                            <option value="price-desc">Sortera efter pris: högt till lågt </option>
                        </select> --}}
                    </div>
                   {{--  <div class="change-view pull-right"><a href="#" title="Grid" class="grid-view"> <i
                            class="fa fa-th-large"></i> </a> <a href="#" title="List" class="list-view "><i
                            class="fa fa-th-list"></i></a></div> --}}
                </div>
            </div>
            <!--/.productFilter-->
            
            @include('search.partials.search_result.search_rims')
            <!--/.categoryFooter-->
        </div>
        <!--/right column end-->    
    </div>
    <!--/.featuredPostContainer-->
</div>
<!-- /main container -->


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
{{-- <script src="{{ asset('assets/js/myCustomScripts/search_rims.js') }}"></script> --}}
<script src="{{ elixir('js/customScripts/search_rims.js') }}"></script>
{{-- <script src="../../../../dackline/{{ elixir('js/customScripts/search_rims.js') }}"></script> --}}


@endsection