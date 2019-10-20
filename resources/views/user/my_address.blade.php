@extends('layout')

@section('content')

<div class="container main-container containerOffset">

    <div class="row">

        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('konto') }}"> Mitt Konto</a></li>
                <li class="active"> Ange Adresser</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">

            <h1 class="section-title-inner"><span><i class="fa fa-map-marker"></i> Ange Dina adresser </span></h1>

            <div class="row userInfo">


                <div class="col-lg-12 col-xs-12">
                    {{-- <h2 class="block-title-2"> För att lägga till en ny adress, var snäll och fyll i formuläret nedan. </h2> --}}

                    
                </div>

                <form action="{{ url('updateAddress') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('patch') }}

                    <div class="col-xs-12 col-sm-6">
                        <h2 class="block-title-2">Faktura Adress</h2>
                        <p class="required"><sup>*</sup> Obligatoriska fält</p>


                        {{-- <div class="form-group required">
                            <label for="InputName">Förnamn <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputName" placeholder="Förnamn">
                        </div>

                        <div class="form-group required">
                            <label for="InputLastName"> Efternamn <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputLastName" placeholder="Efternamn">
                        </div>

                        <div class="form-group">
                            <label for="InputCompany">Företag </label>
                            <input type="text" class="form-control" id="InputCompany" placeholder="Företag">
                        </div> --}}

                        <div class="form-group required">
                            <label>Fullständigt namn <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputBillingFullName" name="InputBillingFullName" placeholder="Namn" value="{{ Auth::user()->billing_full_name}}">
                        </div>

                        <div class="form-group required">
                            <label>Adress <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputBillingAddress" name="InputBillingAddress" placeholder="Adress" value="{{ Auth::user()->billing_street_address}}">
                        </div>

                        <div class="form-group required">
                            <label>Postnummer <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputBillingPostalCode" name="InputBillingPostalCode" placeholder="Postnummer" value="{{ Auth::user()->billing_postal_code}}">
                        </div>
                   
                        <div class="form-group required">
                            <label>Stad <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputBillingCity" name="InputBillingCity" placeholder="Stad" value="{{ Auth::user()->billing_city}}">
                        </div>

                        <div class="form-group required">
                            <label for="InputCountry">Land <sup>*</sup> </label>
                            <select required class="form-control" id="InputBillingCountry" name="InputBillingCountry" disabled>
                            @foreach($countries as $key => $country)
                                @if(Auth::user()->billing_country == $country)
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

                        <div class="form-group required">
                            <label>Fullständigt namn <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputShippingFullName" name="InputShippingFullName" placeholder="Namn" value="{{ Auth::user()->shipping_full_name}}">
                        </div>

                        <div class="form-group required">
                            <label>Adress <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputShippingAddress" name="InputShippingAddress" placeholder="Adress" value="{{ Auth::user()->shipping_street_address }}">
                        </div>

                        <div class="form-group required">
                            <label>Postnummer <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputShippingPostalCode" name="InputShippingPostalCode" placeholder="Postnummer" value="{{ Auth::user()->shipping_postal_code }}">
                        </div>
                   
                        <div class="form-group required">
                            <label>Stad <sup>*</sup> </label>
                            <input required type="text" class="form-control" id="InputShippingCity" name="InputShippingCity" placeholder="Stad" value="{{ Auth::user()->shipping_city }}">
                        </div>

                        <div class="form-group required">
                            <label for="InputCountry">Land <sup>*</sup> </label>
                            <select required class="form-control" id="InputShippingCountry" name="InputShippingCountry" disabled>
                            @foreach($countries as $key => $country)
                                @if(Auth::user()->shipping_country == $country)
                                    <option selected="selected" value="{{$country}}">{{$key}}</option>
                                @else
                                    <option value="{{$country}}">{{$key}}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>


                    </div>


                    {{-- <div class="col-xs-12 col-sm-6">


                        <div class="form-group">
                            <label for="InputAdditionalInformation">Övrig information</label>
                            <textarea rows="3" cols="26" name="InputAdditionalInformation" class="form-control"
                                      id="InputAdditionalInformation"></textarea>
                        </div>

                        <div class="form-group required">
                            <label for="InputMobile">Mobilnummer <sup>*</sup></label>
                            <input required type="tel" name="InputMobile" class="form-control" id="InputMobile">

                        </div>


                        <div class="form-group required">
                            <label for="addressAlias">var snäll och namnge denna adress, för framtida användning. <sup>*</sup></label>

                            <input required type="text" value="My address" name="addressAlias" class="form-control"
                                   id="addressAlias">

                        </div>


                    </div>
 --}}
                    {{-- <div class="col-lg-12 col-xs-12 clearfix">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-map-marker"></i> Spara Adress
                        </button>
                    </div> --}}
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Spara</button>
                    </div>

                </form>


                <div class="col-lg-12 col-xs-12  clearfix ">

                    <ul class="pager">
                        {{-- <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Go to Shop </a>
                        </li> --}}
                        <li class="next pull-left"><a href="{{ url('konto') }}">&larr; Tillbaka till Mitt Konto</a></li>
                    </ul>
                </div>

            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5">
        </div>

    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /wrapper -->

@endsection

@section('footer')
    {{-- <script src="{{ asset('assets/js/myCustomScripts/user_account.js') }}"></script> --}}
    <script src="{{ elixir('js/customScripts/user_account.js') }}"></script>
@endsection
