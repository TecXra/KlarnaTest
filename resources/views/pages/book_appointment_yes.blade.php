@extends('layout')

@section('content')

<div class="wrapper whitebg">
    <div class="container containerOffset">
        <div class="row innerPage">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="row userInfo">

                    <div class="col-xs-12 col-sm-12">
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <h1 class="title-big text-left section-title-style2">
                            <span> Boka tid </span>
                        </h1>

                        <iframe border="0" frameborder="0" style="width: 100%; height: 1300px; border: none;" src="https://www.gaello.se/WebBooking/?key=LELLJEQ5FVSA3IR" ></iframe>

                    </div>
                </div>
            </div>
        </div>
        <!--/row || innerPage end-->
        <div style="clear:both"></div>
    </div>
    <!-- ./main-container -->
    {{-- <div class="gap"></div> --}}
</div>
<!-- /main-container -->

@endsection