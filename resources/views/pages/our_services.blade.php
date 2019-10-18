@extends('layout')

@section('content')

<style>
    
    .userInfo {
        margin-left: 20px;
    }

    a {
        color: #B54035;
    }
</style>

<div class="wrapper whitebg">
    <div class="container containerOffset">
        <div class="row innerPage">
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-left: 20px; margin-bottom: 100px">

                <div class="row userInfo" >

                    <div class="col-xs-12 col-sm-4" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <img src="{{ asset('images/site/dackhotell.jpg') }}">
                        

                    </div>

                    <div class="col-xs-12 col-sm-8" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <h2 class="title-big text-left section-title-style2">
                            <span> Däckhotell </span>
                        </h2>
                        
                        <p class="lead" >
                            <b>Pris</b><br>
                            Nu endast 400:- / säsong 
                            <br>
                            <br>

                            I priset ingår följande: <br>
                            <ul style="list-style-type: disc; margin-left: 35px; font-size: 1.1em">
                                <li>Säker förvaring</li>
                                <li>Däckskiftning</li>
                                <li>Kontroll av lufttryck vid montering</li>
                                <li>Montering</li>
                            </ul>
                        </p>
                            
                        <br>

                        <p class="lead" >
                            <b>Övrig information</b><br>
                            Vi har anpassat våra förvaringslokaler efter särskilda behov. 
                            När däck lagras mörkt och utan att utsättas för hög värme är åldrandet i gummit marginell. Förhindra kontakt med bensin, olja, fett, lösningsmedel eller ozon (ozon kan bildas av elektriska verktyg, svetsljus, batteriladdare etc). 
                            <br>
                            <br>

                            Däcken lagras stående 
                            <br>
                            <br>

                            <b>Mer information -</b> <a href="{{ asset('assets/dackhotellvilkor.pdf')}}"> Däckhotellvillkor </a>

                            <br>
                            <br>
                        </p>
                    </div>
                </div>

                <hr>
                <br>

                <div class="row userInfo" >

                    <div class="col-xs-12 col-sm-4" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <img src="{{ asset('images/site/hjulinstallning.png') }}">
                        

                    </div>

                    <div class="col-xs-12 col-sm-8" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <h2 class="title-big text-left section-title-style2">
                            <span> Hjulinställning </span>
                        </h2>
                        
                        <p class="lead" >
                            <b>Öppettider</b><br>
                            Måndag - lördag 9-18. Söndag stängt.
                            <br>
                            <br>
                           
                            <b> Pris </b><br>
                            Hjulinställning personbil från 750 kr. <br>
                            BMW, SUV, andra exklusiva bilar och skåpbilar 900 kr 
                            <br>
                            <br>

                            <b>Övrig infromation</b><br>
                            Vid en hjulinställning kontrolleras vinklarna på bilens alla fyra hjul med avancerad mätutrustning, så att de överrensstämmer med fordonstillverkarens uppgifter. De olika vinklarna beskrivs som Toe, Camber, Caster osv som alla har betydelse för hur bilen beter sig i färd rakt fram, i svängar, vid inbromsning och över ojämnheter etc.
                        </p>
                    </div>
                </div>

                <hr>
                <br>

                <div class="row userInfo" >

                    <div class="col-xs-12 col-sm-4" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <img src="{{ asset('images/site/balansering.jpg') }}">
                    </div>

                    <div class="col-xs-12 col-sm-8" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <h2 class="title-big text-left section-title-style2">
                            <span> Balansering </span>
                        </h2>
                        
                        <p class="lead" >
                            <b>Pris</b>
                            <br>
                            <br>

                            13 – 16 tum 150 kr <br>
                            17 – 19 tum 200 kr <br>
                            20 tum + 250 kr <br>
                            (Priserna inkluderar montering och balansering)
                        </p>
                    </div>
                </div>


                <br>
                <br>
                <hr>
                <br>

                <div class="row userInfo" >

                    <div class="col-xs-12 col-sm-4" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <img src="{{ asset('images/site/hjulskifte.jpg') }}">
                        

                    </div>

                    <div class="col-xs-12 col-sm-8" >
                        {{-- <h3 class="section-title style2 text-left"><span>Tillbehör</span></h3> --}}
                        <h2 class="title-big text-left section-title-style2">
                            <span> Hjulskifte 4 hjul </span>
                        </h2>
                        
                        <p class="lead" >
                            <b>Pris</b>
                            <br>
                            <br>

                            200 kr för 4 däck.
                        </p>
                            
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