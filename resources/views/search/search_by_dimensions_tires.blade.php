@extends('layout')

@section('content')

@include('search.partials.form.search_by_dimensions_form')

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
    var productTypeID = {{ $product['productTypeID'] }};

    if (!!window.performance && window.performance.navigation.type === 2) {
        // value 2 means "The page was accessed by navigating into the history"
        console.log('Reloading');
        window.location.reload(); // reload whole page
    }
</script>
{{-- <script src="{{ asset('assets/js/myCustomScripts/search_by_dimensions_tires.js') }}"></script> --}}
<script src="{{ elixir('js/customScripts/search_by_dimensions_tires.js') }}"></script>
{{-- <script src="../../../../dackline/{{ elixir('js/customScripts/search_by_dimensions_tires.js') }}"></script> --}}



@endsection