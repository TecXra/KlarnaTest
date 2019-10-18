@extends('layout')


@section('content')

{{-- <div class="contactOffset contact-intro">


    <div class="w100 map">
        <iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"

                src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=+&amp;q=Turbingatan 13, 19560 Arlandastad (Märsta),Sweden&amp;ie=UTF8&amp;hq=&amp;hnear=&amp;ll=59.61513040000001,16.606037499999957&amp;spn=0.007264,0.021136&amp;t=m&amp;z=14&amp;output=embed">

        </iframe>
    </div>
    
</div> --}}
<!--/.contact-intro || map end-->

<div class=" contact-us" style="margin-top:210px">
    <div class="container main-container ">
        <div class="row innerPage">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="row userInfo">

                    <div class="col-xs-12 col-sm-12">

                        <h1 class="title-big text-left section-title-style2" style="font-size: 3em">
                            <span> Kontakta oss </span>
                        </h1>

                        <p class="lead">
                            {!! $page->content !!}
                        </p>

                        {{-- <p class="lead text-center">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac augue at erat hendrerit
                            dictum. Praesent porta, purus eget sagittis imperdiet, nulla mi ullamcorper metus, id
                            hendrerit metus diam vitae est. Class aptent taciti sociosqu ad litora torquent per conubia
                            nostra, per inceptos himenaeos.
                        </p>
 --}}

                        {{-- <div class="row" style="padding: 0 15px">

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >

                                <h3 class="block-title-3">
                                    Support
                                </h3>

                                <p class="lead">
                                    <strong>
                                        Telefon nummer
                                    </strong>
                                    : 073-243 49 42
                                    <br>
                                    <strong>
                                        Maila oss
                                    </strong>
                                    : order@hjulonline.se
                                </p>


                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                <h3 class="block-title-3">
                                    Adress
                                </h3>

                                 <p class="lead"> --}}
                                    <!-- <strong>
                                        Gata
                                    </strong>
                                    : Turbingatan 13
                                    <br> -->
                                    {{-- <strong>
                                        Adress
                                    </strong>
                                    : Söderbyvägen, Märsta
                                </p>

                            </div> --}}

                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                <h3 class="block-title-3">
                                    Reklamation
                                </h3>

                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac augue at erat
                                    hendrerit dictum. Praesent porta, purus eget sagittis imperdiet, nulla mi
                                    ullamcorper metus

                                    <br>
                                    <strong>
                                        Telefon nummer
                                    </strong>
                                    : 021 35 03 55
                                    <br>
                                    <strong>
                                        Maila oss
                                    </strong>
                                    : info@streetwheels.se
                                </p>


                            </div>
 --}}
                            {{-- <div style="clear:both"></div> --}}
                           
                        </div>
                        <!--/.row-->
                    </div>


                </div>
                <!--/.row  ||  -->

            </div>

            <div class="container contact-intro" >


                <div class="w100 map" style="margin-top: 60px; margin-bottom: 60px;">
                    <iframe width="100%" height="320" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"

                             src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=+&amp;q={{ App\Setting::getStreetAddress() }}, {{ App\Setting::getPostalCode() }} {{ App\Setting::getCity() }}, Sverige&amp;ie=UTF8&amp;hq=&amp;hnear=&amp;ll=59.335033,18.052193&amp;spn=0.006264,0.021136&amp;t=m&amp;z=13&amp;output=embed">

                    </iframe>
                </div>
                
            </div>

        </div>


        <!--/row || innerPage end-->
        <div style="clear:both"></div>
    </div>
    <!-- ./main-container -->


</div>
<!-- /wrapper -->

@endsection

@section('footer')


{{-- I think their is an underlying problem with the way the dns configuration was made during the move to another vps. --}}
@endsection


