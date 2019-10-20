{{-- <div class="col-sm-6" style="padding-left: 0;">
    <div class="row">
        <div class="form-group reg-email col-sm-12">
            <div>
                <label>Företagsnamn:</label>
                <input name="companyName" class="form-control input" size="20" placeholder="Företagsnamn" type="text" value="{{$customerInfo['companyName']}}">
            </div>
        </div>
    </div>

    @if(isset($customerInfo['isCompany']))
        <input type="hidden" name="isCompanyCheck" value="{{$customerInfo['isCompany']}}">
    @endif

    <div class="row">
        <div class="form-group reg-email col-sm-6 required">
            <label>E-post: <sup>*</sup></label>
            <input required name="email" class="form-control input" size="20" placeholder="E-post" type="text" value="{{ @Session::get('orderInfo.email') }}">
        </div>

        <div class="form-group reg-email col-sm-6 required">
            <label>Telefon:<sup>*</sup></label>
            <input required name="billingPhone" class="form-control input" size="20" placeholder="Telefon" type="text" value="{{ Auth::check() ? Auth::user()->billing_phone :null }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group reg-email col-sm-6 required">
            <label>Förnamn:<sup>*</sup></label>
            <input required name="firstName" class="form-control input" size="20" placeholder="Förnamn" type="text" value="{{$customerInfo['firstName']}}">
        </div>

        <div class="form-group reg-email col-sm-6 required">
            <label>Efternamn:<sup>*</sup></label>
            <input required name="lastName" class="form-control input" size="20" placeholder="Efternamn" type="text" value="{{$customerInfo['lastName']}}">
        </div>
    </div>
 --}}
    @foreach($customerInfoList as $key => $address)
        <label {{-- class="checkbox-inline" for="checkboxes-0" --}}>
            <input checked name="alternativeAddress" class="alternativeAddress" id="modalPrivate" value="{{$key}}" type="radio"> {{$address['address']}}</label> <br>
    @endforeach

    <br>
{{-- 
    <div class="row">
        <div class="form-group reg-email col-sm-12 required">
            <label>Faktura adress:<sup>*</sup></label>
            <input required id="billingStreetAddress" name="billingStreetAddress" class="form-control input" size="20" placeholder="Faktura adress" type="text" value="{{$customerInfo['address']}}">
        </div>

    </div>

    <div class="row">
        <div class="form-group reg-email col-sm-6 required">
            <label>Postnummer:<sup>*</sup></label>
            <input required id="billingPostalCode" name="billingPostalCode" class="form-control input" size="20" placeholder="Postnummer" type="text" value="{{$customerInfo['postalCode']}}">
        </div>

        <div class="form-group reg-email col-sm-6 required">
            <label>Postort:<sup>*</sup></label>
            <input required id="billingCity" name="billingCity" class="form-control input" size="20" placeholder="Postort" type="text" value="{{$customerInfo['city']}}">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-9 form-group">

            <label>
                <input id="isOrderShippingAddress" name="isOrderShippingAddress" value="1" type="checkbox">
                Alternativ adress för leverans </label>            
            <br>
            <br>
        </div>
    </div>
</div>

<div class="orderShippingAdressForm col-sm-6 hidden" style="padding-right: 0;">
    <div class="row">
        <div class="form-group reg-email col-sm-12 required">
            <label>Mottagarens namn:<sup>*</sup></label>
            <input required name="fullName" class="form-control input" size="20" placeholder="Mottagarens namn" type="text" disabled='disabled'>
        </div>
    </div>

    <div class="row">
        <div class="form-group reg-email col-sm-12 required">
            <label>Leverans adress:<sup>*</sup></label>
            <input required name="shippingStreetAddress" class="form-control input" size="20" placeholder="Leverans adress" type="text" disabled='disabled'>
        </div>

    </div>

    <div class="row">
        <div class="form-group reg-email col-sm-6 required">
            <label>Postnummer:<sup>*</sup></label>
            <input required name="shippingPostalCode" class="form-control input" size="20" placeholder="Postnummer" type="text" disabled='disabled'>
        </div>

        <div class="form-group reg-email col-sm-6 required">
            <label>Postort:<sup>*</sup></label>
            <input required name="shippingCity" class="form-control input" size="20" placeholder="Postort" type="text" disabled='disabled'>
        </div>
    </div>

    <div class="row">
        <div class="form-group reg-email col-sm-12 required">
            <label>Mottagarens telefon:<sup>*</sup></label>
            <input required name="shippingPhone" class="form-control input" size="20" placeholder="Mottagarens telefon" type="text" disabled='disabled'>
        </div>
    </div>

    <div class="form-group">
        <div>

        </div>
    </div>
</div>



<div class="row">

    <div class="col-sm-12">

        <hr>
    
        <button style="margin-left: 5px" type="submit" name="" class="btn btn-primary btn-lg pull-right" title="checkout">Genomför köp &nbsp; <i class="fa fa-arrow-right"></i></button>

    </div>
</div> --}}

   {{--  <div>
        <input name="submit" class="btn  btn-block btn-lg btn-primary" value="REGISTRERA" type="submit">
    </div> --}}
