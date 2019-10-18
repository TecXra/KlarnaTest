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

                        <div style="margin-left: 20px; margin-bottom: 100px">

                           <p class="lead">
                                <ul class="lead">
                                    <li> - Endast kunder som har (Däckförvaring) hos oss som kan boka tid.</li>
                                    <li> - Reg nummer som bokas och inte har någon förvaring hos oss Gäller Inte.</li>
                                    <li> - Detta gäller även dig som vill förvara dina hjul på nytt hos oss.</li>
                                </ul>
                            </p>

                            <br>
                            <br>
                            
                            <div class="row row-centered">
                                <h2 style="font-size: 2.5em" >ÄR DU BEFINTLIG FÖRVARINGS KUND?</h2>
                            </div>

                            <div class="row ">
                                <div class="col-xs-6 ">
                                    <a href="{{ url('boka_tid_ja') }}" type="button" class="btn btn-xxlarge btn-success pull-right">Ja</a>
                                </div>    
                                <div class="col-xs-6 ">
                                    <a href="{{ url('/') }}" type="button" class="btn btn-xxlarge btn-danger pull-left">Nej</a>
                                </div>    
                            </div>

                            <br>

                            <div class="row">
                                <p class="lead text-center">
                                    Vid övriga ärenden är du välkommen in på drop-in.
                                </p>
                            </div>

                            <hr>

                            <div class="row ">
                                <div class="col-xs-12 col-sm-offset-3">
                                    <a style="margin: 0 auto" href="{{ url('boka_tid_ja') }}" type="button" class="btn btn-xxlarge btn-default col-xs-12 col-sm-6 ">Boka hjulinställning</a>
                                </div>      
                            </div>
                        </div>

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