@extends('layout')

@section('content')

<!-- /.Fixed navbar  -->
<div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('varukorg') }}">Varukorg</a></li>
                <li class="active"> Kassa</li>
            </ul>
        </div>
    </div>
    <!--/.row-->
    @if (session()->has('error_message'))
        <div class="alert alert-danger">
            {!! session()->get('error_message') !!}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i
                    class="glyphicon glyphicon-shopping-cart"></i> KASSA</span></h1>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
            {{-- <h4 class="caps"><a href="category.html"><i class="fa fa-chevron-left"></i> Tillbaka till produktsida </a></h4> --}}
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">


                    <div class="w100 clearfix">
                        <div class="row userInfo">

                            <div style="clear: both"></div>
                            
                            <div class="onepage-checkout col-lg-12">

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion"
                                                   href="#BillingInformation" aria-expanded="true"
                                                   aria-controls="BillingInformation">
                                                    Konto & Adressuppgifter
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="BillingInformation" class="panel-collapse collapse in" role="tabpanel"
                                             aria-labelledby="BillingInformation">
                                            <div class="panel-body">

                                                <form id="saveOrderInfo" style="padding:15px">
                                                    {{ csrf_field() }}


                                                    @if( !Auth::check() )
                                                        <div class="col-xs-12">
                                                            
                                                            <h2 class="block-title-2">Ny kund (Gäst konto)</h2>

                                                            <div class="row">
                                                                <div class="form-group required col-xs-12 col-sm-6">
                                                                    <label>Förnamn <sup>*</sup> </label>
                                                                    <input required type="text" class="form-control" id="InputFirstName" name="InputFirstName" placeholder="Förnamn" value="{{  !isset($orderInfo['firstName']) ? null : $orderInfo['firstName'] }}">
                                                                </div>
                                                         
                                                                <div class="form-group required col-xs-12 col-sm-6">
                                                                    <label>Efternamn <sup>*</sup> </label>
                                                                    <input required type="text" class="form-control" id="InputLastName" name="InputLastName" placeholder="Efternamn" value="{{  !isset($orderInfo['lastName']) ? null : $orderInfo['lastName'] }}">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group required col-xs-12 col-sm-6">
                                                                    <label>E-post <sup>*</sup> </label>
                                                                    <input required type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="E-post" value="{{  !isset($orderInfo['email']) ? null : $orderInfo['email']}}">
                                                                </div>

                                                                <div class="form-group required col-xs-12 col-sm-6 hidden">
                                                                    <label>Företagsnamn <sup>*</sup> </label>
                                                                    <input type="text" class="form-control" id="InputCompanyName" name="InputCompanyName" placeholder="Företagsnamn" value="{{  !isset($orderInfo['companyName']) ? null : $orderInfo['companyName']}}">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class=" form-group col-xs-12">

                                                                    <label class="checkbox-inline" for="checkboxes-0">
                                                                        <input checked name="isCompany" id="private" value="0" type="radio">
                                                                        Privat </label>
                                                                    <label class="checkbox-inline" for="checkboxes-1">
                                                                        <input name="isCompany" id="company" value="1" type="radio">
                                                                        Företag </label>

                                                                    <br>
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="col-xs-12 col-sm-6">
                                                        <h2 class="block-title-2">Faktura Adress</h2>
                                                        <p class="required"><sup>*</sup> Obligatoriska fält</p>

                                                        <div class="form-group required">
                                                            <label>Fullständigt namn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingFullName" name="InputBillingFullName" placeholder="Namn" value="{{  isset($orderInfo['billingFullName']) ? $orderInfo['billingFullName'] : @Auth::user()->billing_full_name}}">
                                                        </div>

                                                        <div class="form-group required">
                                                                <label>Telefonnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputBillingPhoneNumber" name="InputBillingPhoneNumber" placeholder="ex: 07612312312" value="{{ isset($orderInfo['billingPhoneNumber']) ? $orderInfo['billingPhoneNumber'] : @Auth::user()->billing_phone }}">
                                                        </div>

                                                        <div class="form-group required">
                                                            <label>Adress <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingAddress" name="InputBillingAddress" placeholder="Adress" value="{{ isset($orderInfo['billingAddress']) ? $orderInfo['billingAddress'] : @Auth::user()->billing_street_address}}">
                                                        </div>

                                                        <div class="form-group required">
                                                            <label>Postnummer <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingPostalCode" name="InputBillingPostalCode" placeholder="Postnummer" value="{{ isset($orderInfo['billingPostalCode']) ? $orderInfo['billingPostalCode'] : @Auth::user()->billing_postal_code}}">
                                                        </div>
                                                   
                                                        <div class="form-group required">
                                                            <label>Stad <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingCity" name="InputBillingCity" placeholder="Stad" value="{{ isset($orderInfo['billingCity']) ? $orderInfo['billingCity'] : @Auth::user()->billing_city}}">
                                                        </div>

                                                        <div class="form-group required">
                                                            <label for="InputCountry">Land <sup>*</sup> </label>
                                                            <select required class="form-control" id="InputBillingCountry" name="InputBillingCountry">
                                                            @foreach($countries as $key => $country)
                                                                @if( (isset($orderInfo['billingCountry']) && $orderInfo['billingCountry'] == $country))
                                                                    <option selected="selected" value="{{$country}}">{{$key}}</option>
                                                                @elseif(!isset($orderInfo['billingCountry']) && @Auth::user()->billing_country == $country)
                                                                    <option selected="selected" value="{{$country}}">{{$key}}</option>
                                                                @else
                                                                    <option value="{{$country}}">{{$key}}</option>
                                                                @endif
                                                            @endforeach
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="col-xs-12 col-sm-6">
                                                        <h2 class="block-title-2">Leverans Adress</h2>
                                                        <div style="margin-bottom: 5px">
                                                            <input type="checkbox" id="autofillShipping"> <label>Använd samma som faktura adress</label>
                                                        </div>
                                                        {{-- <div style="margin-bottom: 5px">
                                                            <input type="checkbox" id="newShippingAddress"> <label>Använd en annan adress för leverans</label>
                                                        </div> --}}


                                                        <div class="form-group required">
                                                            <label>Fullständigt namn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingFullName" name="InputShippingFullName" placeholder="Namn" value="{{  isset($orderInfo['shippingFullName']) ? $orderInfo['shippingFullName'] : @Auth::user()->shipping_full_name}}">
                                                        </div>

                                                        <div class="form-group required">
                                                                <label>Telefonnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputShippingPhoneNumber" name="InputShippingPhoneNumber" placeholder="ex: 07612312312" value="{{ isset($orderInfo['shippingPhoneNumber']) ? $orderInfo['shippingPhoneNumber'] : @Auth::user()->shipping_phone }}">
                                                        </div>

                                                        {{-- <div class="form-group required">
                                                            <label>Efternamn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingLastName" name="InputShippingLastName" placeholder="Efternamn" value="{{ @Auth::user()->shipping_last_name}}">
                                                        </div>
--}}
                                                        <div class="form-group required">
                                                            <label>Adress <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingAddress" name="InputShippingAddress" placeholder="Adress" value="{{ isset($orderInfo['shippingAddress']) ? $orderInfo['shippingAddress'] : @Auth::user()->shipping_street_address }}">
                                                        </div>

                                                        <div class="form-group required">
                                                            <label>Postnummer <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingPostalCode" name="InputShippingPostalCode" placeholder="Postnummer" value="{{ isset($orderInfo['shippingPostalCode']) ? $orderInfo['shippingPostalCode'] : @Auth::user()->shipping_postal_code }}">
                                                        </div>
                                                   
                                                        <div class="form-group required">
                                                            <label>Stad <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingCity" name="InputShippingCity" placeholder="Stad" value="{{ isset($orderInfo['shippingCity']) ? $orderInfo['shippingCity'] : @Auth::user()->shipping_city }}">
                                                        </div>

                                                        <div class="form-group required">
                                                            <label for="InputCountry">Land <sup>*</sup> </label>
                                                            <select required class="form-control" id="InputShippingCountry" name="InputShippingCountry" data-toggle="tooltip" title="Obs! Leveranspris ändras efter leverans land. Dock alltid fri leverans på kompletta hjul.">
                                                            @foreach($countries as $key => $country)
                                                                @if( (isset($orderInfo['shippingCountry']) && $orderInfo['shippingCountry'] == $country))
                                                                    <option selected="selected" value="{{$country}}">{{$key}}</option>
                                                                @elseif(!isset($orderInfo['shippingCountry']) && @Auth::user()->shipping_country == $country)
                                                                    <option selected="selected" value="{{$country}}">{{$key}}</option>
                                                                @else
                                                                    <option value="{{$country}}">{{$key}}</option>
                                                                @endif
                                                            @endforeach
                                                            </select>
                                                        </div>

                                                    </div>




                                                    <div class="col-xs-12">
                                                        
                                                        <h2 class="block-title-2">Övrig information</h2>

                                                        <div class="row">

                                                            <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>Bilens reg.nummer <sup>*</sup> </label>
                                                                <input style="text-transform: uppercase;" required type="text" class="form-control" id="InputRegNumber" name="InputRegNumber" placeholder="Ex: ABC 123. Om okänt ange 000" value="{{ strlen($regNumber) >= 6 ? $regNumber: @$orderInfo['regNumber'] }}">
                                                            </div>

                                                            <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>Reference </label>
                                                                <input type="text" class="form-control" id="InputReference" name="InputReference">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Meddelande </label>
                                                                <textarea class="form-control" id="message" name="message" rows=5></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal content-->
                                                      <!-- Modal -->
                                                      <div class="modal fade col-sm-6 col-sm-offset-3" id="myModal" role="dialog">
                                                        <div class="modal-dialog">
                                                        
                                                          <!-- Modal content-->
                                                          <div class="modal-content">
                                                            <div class="modal-header">
                                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                              <h2 class="modal-title">Köpvillkor</h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h3>Upprättande av avtal om köp</h3>

                                                                <p>Avtal om köp upprättas då din beställning har genomgått alla steg i kassan och ett köp har slutförts. Genom beställningen accepterar du dessa Villkor. För att handla på Streetwheels.se måste du ha fyllt 18 år.
                                                                </p>
                                                                <br>

                                                                <h3>Ångerrätt</h3>

                                                                <p>När du handlar på Streetwheels.se har du alltid möjlighet att inom 14 dagar, från den dagen du tar emot varan, byta eller ångra ditt köp. För att ångerrätten ska gälla måste du kontakta oss inom dessa 14 dagar. Outlösta paket omfattas inte av ångerrätten.
                                                                </p>
                                                                <br>

                                                                <h3>Returfrakt och återbetalning</h3>

                                                                <p>När du ångrar ett köp måste du själv betala kostnaderna för returfrakten. Det enklaste är att skicka tillbaka varan via Posten eller Bussgods via en ombudsstation nära dig. Ångerrätten gäller under förutsättning att varan hålls i väsentligen oförändrat skick, fälgar som sålts av oss där däck som INTE sålts av oss monterats omfattas inte av ångerrätten. Återbetalning sker senast 14 dagar efter att vi har mottagit returen. Vi tar inte emot returer skickade mot postförskott.
                                                                </p>
                                                                <br>

                                                                <h3>Reklamation</h3>

                                                                <p>Enligt konsumentköplagen har du som konsument 3 års reklamationsrätt. Endast ursprungliga fel omfattas av reklamationsrätten och gäller till exempel inte vid fel som orsakas av normalt slitage. Reklamation till Streetewheels AB ska göras så snart felet upptäcks. Streetwheels AB följer Allmänna reklamationsrättens rekommendationer vid eventuell tvist.Vid godkänd reklamation står vi för kostnaden för returfrakten.
                                                                <br><br> 

                                                                Reklamation gäller EJ då följande rekommendationer ej följts:
                                                                <br><br> 

                                                                *Rengöring av fälgar bör ske med vatten och milt bilschampo. 
                                                                *Användning av starkare medel eller vissa typer av fälgrengöringsmedel kan orsaka ytskador. 
                                                                *Använd fälgvax för förlängd livslängd och extra skydd.
                                                                *Applicera aldrig starka medel eller kemikalier på varma/upphettade fälgar
                                                                *Vi rekommenderar inte att man använder fälgar utan heltäckande lack på vintern, t ex kromfälgar eller fälgar med polerad kant.
                                                                <br><br> 

                                                                När du tagit emot ditt paket är det ditt ansvar som kund att anmärka på synliga fraktskador på paketet. Om du upptäcker synliga skador måste detta anmärkas till fraktbolaget på plats.
                                                                Vi kan inte ersätta fraktskador utan att de anmälts till fraktbolaget.
                                                                </p>
                                                                <br>


                                                                <h3>Betalsätt </h3>
                                                                <br>

                                                                <p><img height="50" src="{{ asset('images/site/payment/SVEA_faktura_white_sv.png') }}" alt='img'>
                                                                </p> 
                                            
                                                                <h3>Faktura</h3>
                                                                <p>Vid betalning via faktura samarbetar vi med Svea Ekonomi AB. För att handla mot faktura måste du ange ditt personnummer eller organisationsnummer. Förutsättning för att få handla mot faktura är bland annat att du är registrerad i folkbokföringsregistret i Sverige och är över 18 år. Du får inte ha några betalningsanmärkningar. Samtliga fakturor är av Streetwheels.se överlåtna till Svea Ekonomi AB. Fakturans betalningsvillkor är 14 dagar. Vid försenad betalning utgår avtalad samt lagstadgad påminnelseavgift. Dröjsmålsränta utgår med 2 % per månad. Vid utebliven betalning överlämnas fakturan till inkasso.
                                                                <br><br> 

                                                                Sedvanlig kreditprövning sker efter att personuppgifterna lämnats i kassan, i vissa fall innebär detta att en kreditupplysning tas. En kopia på kreditupplysningen kommer i så fall skickas hem till dig.
                                                                </p>
                                                                <br>

                                                                <p><img height="50" src="{{ asset('images/site/payment/SVEA_delbetala_white_sv.png') }}" alt='img'>
                                                                </p> 

                                                                <h3>Delbetalning </h3>
                                                                <p>Genom vårt samarbete med Svea Ekonomi AB kan du teckna ett kontokreditavtal och därmed kunna delbetala ditt köp. Kredittiden väljer du genom att kryssa i det kampanjalternativ som passar dig bäst. Köpet kan alltid betalas i sin helhet närsomhelst, innan förfallodag.
                                                                <br><br> 

                                                                Sedvanlig kreditprövning sker efter att personuppgifterna lämnats i kassan, i vissa fall innebär detta att en kreditupplysning tas. En kopia på kreditupplysningen kommer i så fall skickas hem till dig.
                                                                <br><br> 

                                                                Har du några frågor så kan du ringa 08-51493113 för närmare information.
                                                                <br><br> 

                                                                Exempel på effektiv ränta vid köp om 10 000 kr, löptid 12 månader, 0 % ränta, uppläggningsavgift 195 kr, aviavgift 29 kr: 10,68 %.
                                                                <br><br> 

                                                                Allmänna villkor hittar du <a href="https://webpay.svea.com/sv/swe/kontovillkor/"><u>här</u></a>.
                                                                <br><br>

                                                                Standardiserad europeisk konsumentkreditinformation hittar du <a href="https://webpay.svea.com/sv/swe/kontovillkor/"><u>här</u></a>.
                                                                </p>
                                                                <br>

                                                                <p><img height="40" src="{{ asset('images/site/payment/kort-188px.png') }}" alt='img'>
                                                                </p> 

                                                                <h3>Kortbetalning</h3>
                                                                <p>Betalning med kort sker enligt gällande regler för online-betalning i Sverige och EU.
                                                                Vi använder Svea Ekonomis/Webpays hostade PCI-DSS-certifierade kortbetalningslösning.
                                                                <br><br>

                                                                Vi accepterar följande kort: Visa, MasterCard. <br>Pengarna reserveras på ditt konto vid
                                                                ordertillfället.
                                                                <br><br>

                                                                Alla transaktioner skickas via SSL (Secure Sockets Layer) mycket säkert krypterade.
                                                                Inga kortnummer sparas. Vi använder den senaste säkerhetstekniken för kontokortsbetalningar på
                                                                nätet 3D-Secure – en standard framtagen av VISA och MasterCard med avsikt att på ett säkert sätt
                                                                validera konsumenten vid köp på nätet.
                                                                </p>
                                                                <br>

                                                                <h3>Förskottsbetalning</h3>
                                                                <p>
                                                                Vid förskottsbetalning ska hela summan inkl. frakt betalas in på vårat plusgirokonto med namn eller ordernummer som referens:
                                                                <br><br>

                                                                Plusgiro: 66 07 84-0
                                                                <br><br>

                                                                Bankgiro: 140-6586
                                                                </p>
                                                                <br>

                                                                <h3>Fel och ändringar av publicerat material</h3>

                                                                <p>Vid eventuella felaktigheter i produkttexter, bilder och priser på webbplatsen förbehåller sig Streetwheels AB att korrigera detta i efterhand. All bildinformation ska ses som illustrationer och kan inte garanteras återge exakt utseende. Vi reserverar oss för eventuella justeringar av priser som till exempel kan bero på prisändringar från våra leverantörer, valutaförändringar, lagerförändringar och eventuellt tekniska fel på webbplatsen.
                                                                </p>
                                                                <br>

                                                                <h3>Priser och frakter</h3>

                                                                <p>Alla priser på Streetwheels.se är angivna i svenska kronor inklusive 25% moms. Det framgår alltid tydligt vad du ska betala i kassan, innan du skickar iväg din order. Extrapriser eller andra erbjudanden gäller så långt lagret räcker.
                                                                </p>
                                                                <br>

                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Stäng</button>
                                                            </div>
                                                          </div>
                                                          
                                                        </div>
                                                      </div>
                                                                                                    
                                                    <div class="row">
                                                        <div style="margin-bottom: 20px; margin-left: 15px;" class="form-group required col-xs-12">
                                                            <input required type="checkbox" id="conditions"> <label>Jag godkänner köpvillkoren. 
                                                                (<a data-toggle="modal" data-target="#myModal">Läs köpvillkoren</a>)
                                                                <sup>*</sup>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Spara</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingTwo">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                   data-parent="#accordion" href="#Shippinginformation"
                                                   aria-expanded="false" aria-controls="collapseTwo">
                                                    Välj leveransmetod
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="Shippinginformation" class="panel-collapse collapse" role="tabpanel"
                                             aria-labelledby="Shippinginformation">
                                            <div class="panel-body">

                                                
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingTwo">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                   data-parent="#accordion" href="#paymentMethods"
                                                   aria-expanded="false" aria-controls="collapseTwo">
                                                    Välj betalningsmetod
                                                </a>
                                            </h4>
                                        </div>
                                       {{--  <div id="paymentMethods" class="panel-collapse collapse" role="tabpanel"
                                             aria-labelledby="paymentMethods">
                                            <div class="panel-body">

                                                {!! $snippet !!}

                                                <a  role="button" class="btn btn-default"
                                                 href="{{ url('faktura') }}" style="background-color:#fff; color: #444; font-size: 1.4em; font-weight: 500; border:1px solid #aaa; margin-top: 15px; margin-bottom: 15px; width: 100%;">
                                                    <img height="50" class="pull-left" src="{{ asset('images/site/payment/SVEA_faktura_white_sv.png') }}" alt='img'> 
                                                    <span style="padding-top: 10px; padding-left: 15px" class="pull-left">Betala hela beloppet med faktura</span>
                                                </a>

                                                <a role="button" class="btn btn-default" href="{{ url('delbetala') }}" style="background-color:#fff; color: #444; font-size: 1.4em; font-weight: 500;  border:1px solid #aaa; width: 100%;">
                                                    <img height="50" class="pull-left" src="{{ asset('images/site/payment/SVEA_delbetala_white_sv.png') }}" alt='img'> 
                                                    <span style="padding-top: 10px; padding-left: 15px" class="pull-left">Delbetala beloppet i <wbr> 3, 6, 12 eller 24 månader</span>
                                                </a>


                                            </div> 
                                        </div> --}}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--/row end-->

                    </div>


                </div>
            </div>
            <!--/row end-->

        </div>

        <div class="col-lg-3 col-md-3 col-sm-12 rightSidebar">
            {{-- <div id="hej"> --}}
            <div class="w100 cartMiniTable">
                <table id="cart-summary" class="std table">
                    <tbody>
                    <tr>
                        <td>Totalt produkter</td>
                        <td class="price"><span id="totalPriceWithouShipping">{{ $cartCalculator->totalPriceProducts() }} {{ $cartCalculator->getCurrency() }}</span></td>
                    </tr>
                    <tr style="">
                        <td>Leverans</td>
                        @if(empty($cartCalculator->totalPriceShipping()))
                            <td class="price shippingCost"><span class="success"> Fri leverans </span></td>
                        @else
                            <td class="price shippingCost">{{ $cartCalculator->totalPriceShipping() }} {{ $cartCalculator->getCurrency() }}</td>

                        @endif
                    </tr>
                    <tr class="cart-total-price ">
                        <td>Totalt (ex moms)</td>
                        <td class="price" id="totalPriceExTax">{{ $cartCalculator->totalPriceExTax() }} {{ $cartCalculator->getCurrency() }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Moms</td>
                        <td class="price" id="total-tax">{{ str_replace(' ', '', Cart::total()) * 0.2}} {{ $cartCalculator->getCurrency() }}</td>
                    </tr>
                    <tr>
                        <td> Totalt</td>
                        <td class=" site-color" id="total-price">{{  str_replace(' ', '', Cart::total()) + $shippingCost }} {{ $cartCalculator->getCurrency() }}</td>
                    </tr> --}}
                    @if(Session::has('campaign'))
                        <tr>
                            <td>Moms</td>
                            <td class="price" id="totalTax">{{ ($cartCalculator->totalTax()) - (Session::get('campaign.discount') * 0.2) }} {{ $cartCalculator->getCurrency() }}</td>
                        </tr>
                        <tr>
                            <td> Rabatt</td>
                            <td class="price" style="color: #D14339;font-size: 22px; font-weight: bold">- <span id="discount">{{ Session::get('campaign.discount') }}</span> {{ $cartCalculator->getCurrency() }}</td>
                        </tr>
                        <tr>
                            <td> Totalt</td>
                            <td class=" site-color" id="total-price">{{ ($cartCalculator->totalPriceIncTax() - Session::get('campaign.discount')) }} {{ $cartCalculator->getCurrency() }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>Moms</td>
                            <td class="price" id="totalTax">{{ $cartCalculator->totalTax() }} {{ $cartCalculator->getCurrency() }}</td>
                        </tr>
                        <tr>
                            <td> Totalt</td>
                            <td class=" site-color" id="total-price">{{ $cartCalculator->totalPriceIncTax() }} {{ $cartCalculator->getCurrency() }}</td>
                        </tr>
                    @endif
                    </tbody>

                </table>
            </div>

            <br>

            <div class="hidden-xs hidden-sm">
                <img src="{{ asset('images/site/christmas-campaign/WP_Julkampanj-2016_banner_300x300px.png') }}">
            </div>

            <div class=" hidden-md hidden-lg">
                <img src="{{ asset('images/site/christmas-campaign/WP_Julkampanj-2016_banner_600x300px.png') }}">
            </div>
            <!--  /cartMiniTable-->
            <br>
            {{-- </div> end hej --}}

        </div>
        <!--/rightSidebar-->

    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /.main-container-->
<div class="gap"></div>
<input type="hidden" id="refreshed" value="no">

@endsection

@section('footer')
    {{-- <script src="{{ asset('assets/js/myCustomScripts/checkout.js') }}"></script> --}}
    <script src="{{ elixir('js/customScripts/checkout.js') }}"></script>
@endsection


