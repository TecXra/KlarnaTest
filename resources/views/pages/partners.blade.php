@extends('layout')

@section('content')


<div class="wrapper whitebg" style="margin-top:90px; margin-bottom: 100px";>
    <div class="container main-container">
        <div class="row innerPage featuredPostContainer globalPadding style2">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-offset-1">
        		<h1 class="title-big text-left section-title-style2">
                    <span>Våra samarbetspartners </span>
                </h1>

                 <div style="padding-left:15px">
                    {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                    
					
                    
		            <p> Vi jobbar med olika leverantörer som gör det möjligt för oss att ha ett prett sortiment på fälgar och däck.</p>

					<br>
				</div>

		        {{-- <div id="productslider" class="owl-carousel owl-theme">
		            <div class="item">
		                <div class="product">
		                    <div class="image" style="background-color: #333;">
		                       	<a style="margin-top: 10px" href="{{ url('http://jr-wheels.se/index.php') }}"><img src="{{ asset('images/logo_jr.png') }}">
		                       	<h2 style="color: #fff">JAPAN RACING</h2></a>
		                    </div>
		                </div>
		            </div>  
					<div class="item">
		                <div class="product">
		                    <div class="image" style="background-color: #333;">
		                       	<a style="margin-top: 10px" href="{{ url('http://jr-wheels.se/index.php') }}"><img src="{{ asset('images/logo_jr.png') }}">
		                       	<h2 style="color: #fff">JAPAN RACING</h2></a>
		                    </div>
		                </div>
		            </div>  
		            <div class="item">
		                <div class="product">
		                    <div class="image" style="background-color: #333;">
		                       	<a style="margin-top: 10px" href="{{ url('http://jr-wheels.se/index.php') }}"><img src="{{ asset('images/logo_jr.png') }}">
		                       	<h2 style="color: #fff">JAPAN RACING</h2></a>
		                    </div>
		                </div>
		            </div> 
		            <div class="item">
		                <div class="product">
		                    <div class="image" style="background-color: #333;">
		                       	<a style="margin-top: 10px" href="{{ url('http://jr-wheels.se/index.php') }}"><img src="{{ asset('images/logo_jr.png') }}">
		                       	<h2 style="color: #fff">JAPAN RACING</h2></a>
		                    </div>
		                </div>
		            </div> 
		        </div> --}}

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