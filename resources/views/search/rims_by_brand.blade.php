@extends('layout')

@section('content')

<div class="container containerOffset">
    <h1>{{ $product['items']->first()->product_brand }} fälgar</h1>
</div>

<div class="container main-container">

    <div id="searchContainer" class="row featuredPostContainer globalPadding style2">
        <div class="col-sm-12">

            <div class="w100 productFilter clearfix">
                <p class="pull-left"> Visar <strong id="productCount">{{ $product['items']->total() }}</strong> produkter </p>

                <div class="pull-right ">
                    <div class="change-view pull-right"><a href="#" title="Grid" class="grid-view"> <i
                            class="fa fa-th-large"></i> </a> <a href="#" title="List" class="list-view "><i
                            class="fa fa-th-list"></i></a></div>
                </div>
            </div>

            @include('search.partials.search_result.search_tires')

        </div>
    </div>
</div>


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
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var page = $(this).text();
    // console.log(url, page);
    // redirect(page);

    $.ajax({
        type: 'GET',
        url: url, 
        data: {
            'page' : page
        },
        dataType: 'json',
        xhrFields: {
            withCredentials: true
        },
        success: function(data) {
            $('#searchResult').remove();
            $('.categoryFooter').remove();
            $('#searchContainer div.col-sm-12').append(data.searchResult);

            $('html, body').animate({scrollTop : $("#searchContainer").offset().top},700);
        }
    });
});
</script>

@endsection