@extends('layout')

@section('header')
    <META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
@endsection

@section('content')

@if(Session::has('myResponse'))
    {{ dd(Session::get('myResponse'), Session::get('myOrder'), Session::get('countryCode')) }}
@endif

<div class="modal signUpContent fade" id="sendToFriendModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h3 class="modal-title-site text-center"> Tipsa en vän </h3>
            </div>
            <div class="modal-body">
                <form action="{{ url('sendToFriend') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group login-username">
                        <div>
                            <input required type="email" name="mailTo" id="login-user" class="form-control input" size="20" placeholder="E-post">
                        </div>
                    </div>
                    <div class="form-group login-password">
                        <div>
                            <input required type="text" name="name" id="login-password" class="form-control input" size="20" placeholder="Avsändarens namn" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <textarea name="mailMessage" class="form-control" rows="5" placeholder="Meddelande"></textarea>
                        </div>
                    </div>
                    <div>
                        <div>
                            <input name="submit" class="btn  btn-block btn-lg btn-primary" value="Skicka" type="submit">
                        </div>
                    </div>

                </form> <!--userForm-->


            </div>
        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal-dialog -->

</div>
<!-- /.Modal Login -->
<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li class="active">Varukorg</li>
            </ul>
        </div>
    </div>
    <!--/.row-->

    @if (session()->has('success_message'))
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    @endif

    @if (session()->has('error_message'))
        <div class="alert alert-danger">
            {{ session()->get('error_message') }}
        </div>
    @endif


    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 col-xxs-12 text-center-xs">
            <h1 class="section-title-inner"><span><i
                    class="glyphicon glyphicon-shopping-cart"></i> Varukorg </span></h1>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar col-xs-6 col-xxs-12 text-center-xs">
            {{-- <h4 class="caps"><a href="category.html"><i class="fa fa-chevron-left"></i> Tillbaka till produktsida </a></h4> --}}
        </div>
        <div class="pull-right" style="margin-right:25px;">
            <a href="{{ url('printCartOrder')}}"><span class="glyphicon glyphicon-print" style="font-size: 1.7em" title="Skriv ut produktlistan"></span></a>
        </div>
        <div class="pull-right" style="margin-right:15px;">
            <a data-toggle="modal" data-dismiss="modal" href="#sendToFriendModal">
                    <span class="glyphicon glyphicon-send" style="font-size: 1.7em" title="Tipsa en vän"></span> </a>
            {{-- <a href="{{ url('tellAFriend')}}"><span class="glyphicon glyphicon-send" style="font-size: 1.7em" title="Tipsa en vän"></span></a> --}}
        </div>
    </div>
    <!--/.row-->

    @if (isset($cantOrder) and $cantOrder)
        <div class="row">
            <div class="col-lg-12">
                <h3><span style="color: red; background-color: yellow; padding:3px">För att kunna erbjuda dig ett konkurens kraftigt pris på dina däck/fälg så är minsta beställningsantal <b>4st</b></span></h3>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <div class="row userInfo">

                <div style="clear: both"></div>

                <div class="onepage-checkout col-lg-12">

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    {{-- <a role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#CartInformation" aria-expanded="true"
                                       aria-controls="CartInformation"> --}}
                                        Varukorg
                                    {{-- </a> --}}
                                </h4>
                            </div>
                            <div id="CartInformation" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="CartInformation">
                                <div class="panel-body">
                                    {{-- <div  style="padding: 20px 15px;"> --}}
                                    {{-- <div class="col-xs-12 col-sm-12"> --}}
                                        @if (sizeof(Cart::content()) > 0)
                                        <div class="cartContent w100 checkoutReview">
                                            <table class="cartTable table-responsive" style="width: 100%" >
                                                <tbody>
                                                <tr class="CartProduct cartTableHeader">
                                                    <td class="cartColumn1"><i class="fa fa-picture-o fa-fw"></i></td>
                                                    <td class="cartColumn2">info</td>
                                                    <td class="cartColumn3 delete">&nbsp;</td>
                                                    <td class="cartColumn4">Antal</td>
                                                    {{-- <td class="cartColumn6">Rabatt</td> --}}
                                                    <td class="cartColumn5">Totalt</td>
                                                </tr>

                                                @foreach (Cart::content() as $item)
                                                <tr class="CartProduct">
                                                    <td class="CartProductThumb">
                                                        <div>
                                                        @if($item->model->product_category_id !== 4)
                                                            <a href="{{ url($item->model->productType->name .'/'. $item->id) }}">
                                                        @else
                                                            <a>
                                                        @endif
                                                        @if(isset($item->model->productImages->first()->thumbnail_path))
                                                           <img src="{{ asset($item->model->productImages->first()->thumbnail_path)}}" alt="img">
                                                        @endif

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="CartDescription">
                                                            @if($item->model->product_category_id !== 4)
                                                                <h4><a href="{{ url($item->model->productType->name .'/'. $item->model->id) }}">{{$item->model->product_name}} </a></h4>
                                                            @else
                                                                <h4> {{$item->model->product_name}} </h4>
                                                            @endif

                                                            {{-- <span class="size">{{ $item->model->product_width .'x'. $item->model->product_inch }}</span> --}}

                                                            <div class="price"><span>{{ $item->price }} kr</span></div>
                                                        </div>
                                                        @if( $item->model->product_category_id === 2 && strpos($item->model->product_name, 'Blank') !== false)
                                                            <div class="row">
                                                            <div class="pull-left " style="margin-left:15px;margin-top:5px">
                                                                Ange ET mellan {{$item->model->et_min}}-{{$item->model->et}}: {{-- <input type="number" min="{{$item->model->et_min}}" max="{{$item->model->et}}" step="1"> --}}
                                                            </div>
                                                            <div class="col-sm-3 ">
                                                                <select class="form-control pull-right DDET" id="DDET" name="DDET" data-id="{{$item->model->id}}" data-rowid="{{$item->rowId}}" value="{{ $item->qty }}">
                                                                    @for ($i = $item->model->et_min; $i <= $item->model->et; $i++)
                                                                        @if($item->options->et == $i)
                                                                            <option selected value="{{$i}}">{{$i}}</option>
                                                                        @else
                                                                            <option value="{{$i}}">{{$i}}</option>
                                                                        @endif
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            </div>

                                                        @endif
                                                    </td>
                                                    {{-- <td class="delete"><a title="Delete"> <i
                                                            class="glyphicon glyphicon-trash fa-2x"></i></a></td> --}}
                                                    <td class="delete">
                                                        @if($item->model->id !== 4)
                                                            <form action="{{ url('varukorg', [$item->rowId]) }}" method="POST" class="side-by-side">
                                                                {!! csrf_field() !!}
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit" style="border:none;background:none;"><i class="glyphicon glyphicon-trash fa-2x"></i></button>
                                                                {{-- <input type="submit" class="btn btn-danger btn-sm" value="Remove"> --}}
                                                            </form>
                                                        @endif
                                                    </td>

                                                    <td><input class="quanitySniper" type="text" data-id="{{$item->model->id}}" data-rowid="{{$item->rowId}}" value="{{ $item->qty }}" name="quanitySniper"></td>
                                                    {{-- <td>0</td> --}}
                                                    <td class="price">{{ $item->total }}kr</td>
                                                </tr>
                                                @endforeach
                    {{--
                                                <tr class="CartProduct">
                                                    <td class="CartProductThumb">
                                                        <div><a href="product-details.html"><img src="images/product/ABS302MK-big.jpg" alt="img"></a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="CartDescription">
                                                            <h4><a href="product-details.html">ABS302 MK </a></h4>
                                                            <span class="size">12 x 1.5 L</span>

                                                            <div class="price"><span>4233kr</span></div>
                                                        </div>
                                                    </td>
                                                    <td class="delete"><a title="Delete"> <i
                                                            class="glyphicon glyphicon-trash fa-2x"></i></a></td>
                                                    <td><input class="quanitySniper" type="text" value="2" name="quanitySniper"></td>
                                                    <td>0</td>
                                                    <td class="price">4233kr</td>
                                                </tr>

                                                <tr class="CartProduct">
                                                    <td class="CartProductThumb">
                                                        <div>
                                                            <a href="product-details.html"><img src="images/product/atrezzo-zsr.jpg" alt="img"></a>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="CartDescription">
                                                            <h4><a href="product-details.html">165/70R14C 89R Dunlop ECONODRIVE </a></h4>
                                                            <span class="size">12 x 1.5 L</span>

                                                            <div class="price"><span>4233kr</span></div>
                                                        </div>
                                                    </td>
                                                    <td class="delete"><a title="Delete"> <i
                                                            class="glyphicon glyphicon-trash fa-2x"></i></a></td>
                                                    <td><input class="quanitySniper" type="text" value="2" name="quanitySniper"></td>
                                                    <td>0</td>
                                                    <td class="price">4233kr</td>
                                                </tr> --}}
                                                </tbody>
                                            </table>

                                            <br>

                                            <div class="row" style=" padding: 20px 40px;">
                                                <div class="col-xs-12">

                                                @if(isset($addedMount))
                                                    {{-- <i class="glyphicon glyphicon-ok" style="color: #55B95E;padding:5px; font-size: 1.5em"></i>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Lägg till montering + balansering (Däck på fälg) </label><br><br> --}}
                                                @else
                                                    {{-- <a href="addMount" id="addMount" class="btn" style="padding:5px; margin:5px"><span class="glyphicon glyphicon-plus" style="color: #55B95E; font-size: 1.5em"></span></a>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Lägg till montering + balansering (Däck på fälg) </label><br><br> --}}

                                                @endif

                                                @if(isset($addedKit))
                                                    {{-- <i class="glyphicon glyphicon-ok" style="color: #55B95E;padding:5px; font-size: 1.5em"></i>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Lägg till montering + balansering (Däck på fälg) </label><br><br> --}}
                                                @else
                                                    <a href="addKit" id="addKit" class="btn" style="padding:5px; margin:5px"><span class="glyphicon glyphicon-plus" style="color: #55B95E; font-size: 1.5em"></span></a>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Lägg till Monteringskit: Bult/Mutter/Navring </label><br><br>

                                                @endif

                                                @if(isset($addedTPMS))
                                                    {{-- <i class="glyphicon glyphicon-ok"  style="color: #55B95E; padding:5px; font-size: 1.5em"></i>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Är din bil utrustad med TPMS? Lägg till 4st sensorer á 599kr. </label><br><br> --}}
                                                @else
                                                    <a href="addTPMS" id="addTPMS" class="btn" style="padding:5px; margin:5px"><span class="glyphicon glyphicon-plus" style="color: #55B95E; font-size: 1.5em"></span></a>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Är din bil utrustad med TPMS? </label><br><br>
                                                @endif

                                                @if(isset($addedCarChange))
                                                    {{-- <i class="glyphicon glyphicon-ok"  style="color: #55B95E; padding:5px; font-size: 1.5em"></i>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Är din bil utrustad med TPMS? Lägg till 4st sensorer á 599kr. </label><br><br> --}}
                                                @else
                                                    {{-- <a href="addCarChange" id="addCarChange" class="btn" style="padding:5px; margin:5px"><span class="glyphicon glyphicon-plus" style="color: #55B95E; font-size: 1.5em"></span></a>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Skifte av bil</label><br><br> --}}
                                                @endif

                                                @if(isset($addedLockKit))
                                                    {{-- <i class="glyphicon glyphicon-ok"  style="color: #55B95E; padding:5px; font-size: 1.5em"></i>
                                                    <label class="checkbox-inline" for="checkboxes-0"> Är din bil utrustad med TPMS? Lägg till 4st sensorer á 599kr. </label><br><br> --}}
                                                @else
                                                    <a href="addLockKit" id="addLockKit" class="btn" style="padding:5px; margin:5px"><span class="glyphicon glyphicon-plus" style="color: #55B95E; font-size: 1.5em"></span></a>
                                                    <label class="checkbox-inline" for="checkboxes-0"> LÅSBULT/ MUTTER KIT</label><br><br>
                                                @endif


                                                </div>
                                            </div>
                                        </div> <!--cartContent-->
                                        @endif


                                        <div class="cartFooter w100">
                                            <div class="box-footer">
                                                <div class="pull-left"><a href="{{ url('') }}" class="btn btn-md btn-default"> <i
                                                        class="fa fa-arrow-left"></i> &nbsp; Fortsätt handla </a>
                                                </div>

                                                <div class="pull-right">
                                                    <form action="{{ url('emptyCart') }}" method="POST">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-md btn-default "><i class="fa fa-undo"></i>  Töm varukorg
                                                        </button>
                                                        {{-- <input type="submit" class="btn btn-danger btn-lg" value="Empty Cart"> --}}
                                                    </form>
                                                    {{-- <button type="submit" class="btn btn-default"><i class="fa fa-undo"></i> &nbsp; Uppdatera varukorg
                                                    </button> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <!--/ cartFooter -->

                                        <div class="w100 hidden-sm hidden-md hidden-lg">
                                            <table id="cart-summary" class="std table">
                                                <tbody>
                                                <tr>
                                                    <td>Totalt produkter</td>
                                                    <td class="price">{{ $cartCalculator->totalPriceProducts() }} {{ $cartCalculator->getCurrency() }}</td>
                                                </tr>
                                                <tr style="">
                                                    <td>Leverans</td>
                                                        <td class="price shippingCost">{{ $cartCalculator->totalPriceShipping() }} {{ $cartCalculator->getCurrency() }}</td>
                                                </tr>
                                                <tr class="cart-total-price ">
                                                    <td>Totalt (ex moms)</td>
                                                    <td id="totalPriceExTax" class="price totalPriceExTax">{{ $cartCalculator->totalPriceExTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                                </tr>

                                                @if(Session::has('campaign'))
                                                    <tr>
                                                        <td>Moms</td>
                                                        <td class="price totalTax" id="totalTax">{{ ($cartCalculator->totalTax() - Session::get('campaign.discount')) * 0.2 }} {{ $cartCalculator->getCurrency() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td> Rabatt</td>
                                                        <td class="" style="color: #D14339;">- {{ Session::get('campaign.discount') }} {{ $cartCalculator->getCurrency() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td> Totalt</td>
                                                        <td class="site-color total-price" id="total-price">{{  $cartCalculator->totalPriceIncTax() - Session::get('campaign.discount') }} {{ $cartCalculator->getCurrency() }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>Moms</td>
                                                        <td class="price totalTax" id="totalTax">{{ $cartCalculator->totalTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td> Totalt</td>
                                                        <td class="site-color total-price" id="total-price">{{  $cartCalculator->totalPriceIncTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                                    </tr>
                                                @endif
                                                {{-- <tr>
                                                    <td colspan="2">
                                                        <div class="input-append couponForm">
                                                            <form action="campaignCode" method="POST">
                                                                {{ csrf_field() }}
                                                                <input class="col-lg-8" name="campaignCode" id="appendedInputButton" type="text"
                                                                       placeholder="Rabatt kod">
                                                                <button type="submit" class="col-lg-4 btn btn-success" type="button">Använd</button>
                                                            </form>


                                                        </div>
                                                    </td>

                                                </tr> --}}
                                                <tr>
                                                    @if ($errors->has('campaignCode'))
                                                    <td colspan="2">
                                                        <span class="help-block has-error">
                                                            <strong>{{ $errors->first('campaignCode') }}</strong>
                                                        </span>
                                                    </td>
                                                    @endif
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>


                                        @if (sizeof(Cart::content()) > 0)
                                        <div class="w100">

                                            {{-- <table id="cart-summary" class="std table">
                                                <tbody> --}}
                                            {{-- <form id="orderInformationForm" method="POST" >
                                                {{ csrf_field() }}
                                                    <h2 class="block-title-2">Order information</h2>

                                                    <div class="row">

                                                        <div class=" required col-xs-6 col-sm-6">

                                                            <label>E-post <sup>*</sup> </label>
                                                            <input required type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="E-post" value="{{  isset($orderInfo['email']) ? $orderInfo['email'] : @Auth::user()->email}}">

                                                            <label>Bilens reg.nummer <sup>*</sup> </label>
                                                            <input style="text-transform: uppercase;" required type="text" class="form-control" id="InputRegNumber" name="InputRegNumber" placeholder="Ex: ABC 123. Om okänt ange 000" value="{{ strlen($regNumber) >= 6 ? $regNumber: @$orderInfo['regNumber'] }}">

                                                            <label>Referens </label>
                                                            <input type="text" class="form-control" id="InputReference" name="InputReference" value="{{  !isset($orderInfo['reference']) ? "" : $orderInfo['reference']}}">
                                                        </div>

                                                        <div class=" required col-xs-6 col-sm-6">
                                                            <label>Meddelande </label>
                                                                <textarea class="form-control" id="message" name="message" rows=10>{{  !isset($orderInfo['message']) ? "" : $orderInfo['message']}}</textarea>
                                                        </div>

                                                    </div>

                                                    @if(sizeof(Cart::content()) > 0)
                                                        <div class="row">
                                                            <div class="col-sm-4 col-md-3 pull-right">
                                                                <button type="submit" name="" class="btn btn-primary btn-lg btn-block " title="checkout" style="margin-top:50px">Gå vidare &nbsp; <i class="fa fa-arrow-right"></i></button>
                                                            </div>
                                                        </div>
                                                    @endif
                                            </form> --}}

                                            <form id="orderInformationForm" style="padding:15px">
                                                    {{ csrf_field() }}


                                                    {{-- @if( !Auth::check() )
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
                                                    @endif --}}

                                                    <div class="col-xs-12 col-md-6">
                                                        <h2 class="block-title-2">Faktura Adress</h2>
                                                        <p class="required"><sup>*</sup> Obligatoriska fält</p>

                                                        <div class="row">
                                                            <div class=" form-group col-xs-12">
                                                                <label class="checkbox-inline" for="checkboxes-0">
                                                                    <input checked name="isCompanyCheckout" id="private" value="0" type="radio">
                                                                    Privat </label>
                                                                <label class="checkbox-inline" for="checkboxes-1">
                                                                    <input name="isCompanyCheckout" id="company" value="1" type="radio"
                                                                    {{ @Auth::user()->is_company ? "checked" : null }}>
                                                                    Företag </label>

                                                                <br>
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="getAdressMessage"></div>

                                                        <div id="getAdressField" class="row" style="padding: 0 15px 10px 15px; margin-bottom: 20px">
                                                            <div class="input-group required">
                                                                <label>Personnr/Orgnr (ÅÅMMDDXXXX)<sup>*</sup></label>
                                                                <input required id="ssn" name="ssn" class="form-control input" size="20" placeholder="ÅÅMMDDXXXX" type="text" value="{{ isset($orderInfo['ssn']) ? $orderInfo['ssn'] : @Auth::user()->org_number }}">
                                                                <span id="ssnError" class="help-block"><strong></strong></span>
                                                                <span class="input-group-btn getPersonalNumberInfoButton">
                                                                    <button class="btn btn-primary" id="getAddress" type="button">H&auml;mta uppgifter!</button>
                                                                </span>
                                                            </div>
                                                        </div>


                                                        <div id="orderFormFields"></div>

                                                        {{-- <div class="row">
                                                            <div class="form-group reg-email col-sm-12 required {{ @Auth::user()->is_company ? '' : 'hidden' }}">
                                                                <label>Företagsnamn: <sup>*</sup></label>
                                                                <input required id="companyNameCheckout" name="companyNameCheckout" class="form-control input" size="20" placeholder="Företagsnamn" type="text" value="{{ isset($orderInfo['companyName']) ? $orderInfo['companyName'] : @Auth::user()->company_name }}" {{ @Auth::user()->is_company ? '' : 'disabled' }}>
                                                            </div>
                                                        </div> --}}

                                                        <div class="row">
                                                            <div class="form-group required col-xs-12 {{ @Auth::user()->is_company ? '' : 'hidden' }}">
                                                                <label>Företagsnamn <sup>*</sup> </label>
                                                                <input type="text" class="form-control" id="InputCompanyName" name="InputCompanyName" placeholder="Företagsnamn" value="{{ isset($orderInfo['companyName']) ? $orderInfo['companyName'] : @Auth::user()->company_name}}" {{ @Auth::user()->is_company ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="form-group required col-xs-12 col-md-6">
                                                                <label>Förnamn <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputFirstName" name="InputFirstName" placeholder="Förnamn" value="{{ isset($orderInfo['firstName']) ? $orderInfo['firstName'] : @Auth::user()->first_name }}">

                                                                <span id="InputFirstNameError" class="help-block2"><strong></strong></span>
                                                            </div>

                                                            <div class="form-group required col-xs-12 col-md-6">
                                                                <label>Efternamn <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputLastName" name="InputLastName" placeholder="Efternamn" value="{{ isset($orderInfo['lastName']) ? $orderInfo['lastName'] : @Auth::user()->last_name }}">

                                                                <span id="InputLastNameError" class="help-block2"><strong></strong></span>
                                                            </div>

                                                        </div>

                                                        {{-- <div class="form-group required">
                                                            <label>Fullständigt namn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingFullName" name="InputBillingFullName" placeholder="Namn" value="{{  isset($orderInfo['billingFullName']) ? $orderInfo['billingFullName'] : @Auth::user()->billing_full_name}}">
                                                        </div> --}}
{{--
                                                        <div class="form-group required">
                                                                <label>Telefonnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputBillingPhoneNumber" name="InputBillingPhoneNumber" placeholder="ex: 07612312312" value="{{ isset($orderInfo['billingPhoneNumber']) ? $orderInfo['billingPhoneNumber'] : @Auth::user()->billing_phone }}">
                                                        </div> --}}

                                                        <div class="form-group required">
                                                            <label>Adress <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingAddress" name="InputBillingAddress" placeholder="Adress" value="{{ isset($orderInfo['billingAddress']) ? $orderInfo['billingAddress'] : @Auth::user()->billing_street_address}}">

                                                            <span id="InputBillingAddressError" class="help-block2"><strong></strong></span>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group required col-xs-12 col-md-6">
                                                                <label>Postnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputBillingPostalCode" name="InputBillingPostalCode" placeholder="Postnummer" value="{{ isset($orderInfo['billingPostalCode']) ? $orderInfo['billingPostalCode'] : @Auth::user()->billing_postal_code}}">

                                                                <span id="InputBillingPostalCodeError" class="help-block2"><strong></strong></span>

                                                            </div>

                                                            <div class="form-group required col-xs-12 col-md-6">
                                                                <label>Stad <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputBillingCity" name="InputBillingCity" placeholder="Stad" value="{{ isset($orderInfo['billingCity']) ? $orderInfo['billingCity'] : @Auth::user()->billing_city}}">

                                                                <span id="InputBillingCityError" class="help-block2"><strong></strong></span>
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group required col-xs-12 col-md-6">
                                                                <label>E-post <sup>*</sup> </label>
                                                                <input required type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="E-post" value="{{ isset($orderInfo['email']) ? $orderInfo['email'] : @Auth::user()->email }}">

                                                                <span id="InputEmailError" class="help-block2"><strong></strong></span>
                                                            </div>

                                                            <div class="form-group required col-xs-12 col-md-6">
                                                                <label>Telefon <sup>*</sup> </label>


                                                                <input required type="phone" class="form-control" id="InputBillingPhoneNumber" name="InputBillingPhoneNumber" placeholder="Telefon" value="{{ isset($orderInfo['billingPhoneNumber']) ? $orderInfo['billingPhoneNumber'] : @Auth::user()->billing_phone }}">

                                                                <span id="InputBillingPhoneNumberError" class="help-block2"><strong></strong></span>
                                                            </div>
                                                        </div>


                                                        <div class="form-group required">
                                                            <label for="InputCountry">Land <sup>*</sup> </label>
                                                            <select required class="form-control" id="InputBillingCountry" name="InputBillingCountry" disabled>
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

                                                        <div style="margin-bottom: 5px">
                                                            <input name="deliveryAdress" type="checkbox" id="autofillShipping"> <label>Ange annan leveransadress</label>
                                                        </div>
                                                        <br>

                                                    </div>

                                                    <div id="deliveryAdress" class="col-xs-12 col-md-6 hidden" >
                                                        <h2 class="block-title-2" style="margin-bottom: 187px">Leverans Adress</h2>
                                                        {{-- <div style="margin-bottom: 5px">
                                                            <input type="checkbox" id="autofillShipping"> <label>Använd samma som faktura adress</label>
                                                        </div> --}}
                                                        {{-- <div style="margin-bottom: 5px">
                                                            <input type="checkbox" id="newShippingAddress"> <label>Använd en annan adress för leverans</label>
                                                        </div> --}}

                                                        <div class="row">
                                                            <div class="form-group col-sm-12 required" style="margin-top: 20px" >
                                                                <label>Fullständigt namn <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputShippingFullName" name="InputShippingFullName" placeholder="Namn" value="{{  isset($orderInfo['shippingFullName']) ? $orderInfo['shippingFullName'] : @Auth::user()->shipping_full_name }}" disabled >
                                                            </div>
                                                        </div>

                                                        {{-- <div class="form-group required">
                                                            <label>Fullständigt namn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputBillingFullName" name="InputBillingFullName" placeholder="Namn" value="{{  isset($orderInfo['billingFullName']) ? $orderInfo['billingFullName'] : @Auth::user()->billing_full_name}}">
                                                        </div> --}}
{{--
                                                        <div class="form-group required">
                                                                <label>Telefonnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputBillingPhoneNumber" name="InputBillingPhoneNumber" placeholder="ex: 07612312312" value="{{ isset($orderInfo['billingPhoneNumber']) ? $orderInfo['billingPhoneNumber'] : @Auth::user()->billing_phone }}">
                                                        </div> --}}

                                                        <div class="form-group required">
                                                            <label>Adress <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingAddress" name="InputShippingAddress" placeholder="Adress" value="{{ isset($orderInfo['shippingAddress']) ? $orderInfo['shippingAddress'] : @Auth::user()->shipping_street_address}}" disabled >
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>Postnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputShippingPostalCode" name="InputShippingPostalCode" placeholder="Postnummer" value="{{ isset($orderInfo['shippingPostalCode']) ? $orderInfo['shippingPostalCode'] : @Auth::user()->shipping_postal_code}}" disabled >

                                                                <span id="InputShippingPostalCodeError" class="help-block2"><strong></strong></span>
                                                            </div>

                                                            <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>Stad <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputShippingCity" name="InputShippingCity" placeholder="Stad" value="{{ isset($orderInfo['shippingCity']) ? $orderInfo['shippingCity'] : @Auth::user()->shipping_city}}" disabled >
                                                            </div>
                                                        </div>

                                                         <div class="row">
                                                           {{--  <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>E-post <sup>*</sup> </label>
                                                                <input required type="email" class="form-control" id="InputShippingEmail" name="InputShippingEmail" placeholder="E-post" value="{{  !isset($orderInfo['email']) ? null : $orderInfo['email']}}">
                                                            </div>
 --}}
                                                            <div class="form-group required col-xs-12 col-sm12" style="margin-bottom: 27px" >
                                                                <label>Telefon <sup>*</sup> </label>
                                                                <input required type="phone" class="form-control" id="InputShippingPhoneNumber" name="InputShippingPhoneNumber" placeholder="Telefon" value="{{  isset($orderInfo['shippingPhoneNumber']) ? $orderInfo['shippingPhoneNumber'] : @Auth::user()->shipping_phone}}" disabled >

                                                                <span id="InputShippingPhoneNumberError" class="help-block2"><strong></strong></span>

                                                            </div>
                                                        </div>

                                                        <div class="form-group required">
                                                            <label for="InputCountry">Land <sup>*</sup> </label>
                                                            <select required class="form-control" id="InputShippingCountry" name="InputShippingCountry" disabled >
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



                                                        {{-- <div class="form-group required">
                                                            <label>Fullständigt namn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingFullName" name="InputShippingFullName" placeholder="Namn" value="{{  isset($orderInfo['shippingFullName']) ? $orderInfo['shippingFullName'] : @Auth::user()->shipping_full_name}}" disabled>
                                                        </div>

                                                        <div class="form-group required">
                                                                <label>Telefonnummer <sup>*</sup> </label>
                                                                <input required type="text" class="form-control" id="InputShippingPhoneNumber" name="InputShippingPhoneNumber" placeholder="ex: 07612312312" value="{{ isset($orderInfo['shippingPhoneNumber']) ? $orderInfo['shippingPhoneNumber'] : @Auth::user()->shipping_phone }}" disabled>
                                                        </div>
 --}}
                                                        {{-- <div class="form-group required">
                                                            <label>Efternamn <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingLastName" name="InputShippingLastName" placeholder="Efternamn" value="{{ @Auth::user()->shipping_last_name}}">
                                                        </div>
--}}
                                                        {{-- <div class="form-group required">
                                                            <label>Adress <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingAddress" name="InputShippingAddress" placeholder="Adress" value="{{ isset($orderInfo['shippingAddress']) ? $orderInfo['shippingAddress'] : @Auth::user()->shipping_street_address }}" disabled>
                                                        </div>

                                                        <div class="form-group required">
                                                            <label>Postnummer <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingPostalCode" name="InputShippingPostalCode" placeholder="Postnummer" value="{{ isset($orderInfo['shippingPostalCode']) ? $orderInfo['shippingPostalCode'] : @Auth::user()->shipping_postal_code }}" disabled>
                                                        </div>

                                                        <div class="form-group required">
                                                            <label>Stad <sup>*</sup> </label>
                                                            <input required type="text" class="form-control" id="InputShippingCity" name="InputShippingCity" placeholder="Stad" value="{{ isset($orderInfo['shippingCity']) ? $orderInfo['shippingCity'] : @Auth::user()->shipping_city }}" disabled>
                                                        </div> --}}

                                                        {{-- <div class="form-group required">
                                                            <label for="InputCountry">Land <sup>*</sup> </label>
                                                            <select required class="form-control" id="InputShippingCountry" name="InputShippingCountry" data-toggle="tooltip" title="Obs! Leveranspris ändras efter leverans land. Dock alltid fri leverans på kompletta hjul." disabled>
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
                                                        </div>--}}
                                                    </div>




                                                    <div class="col-xs-12">

                                                        <h2 class="block-title-2">Övrig information</h2>

                                                        <div class="row">

                                                            <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>Bilens reg.nummer <sup>*</sup> </label>
                                                                <input style="text-transform: uppercase;" required type="text" class="form-control" id="InputRegNumber" name="InputRegNumber" placeholder="Ex: ABC123. Om okänt ange 000000" value="{{ strlen($regNumber) >= 6 ? $regNumber: @$orderInfo['regNumber'] }}">

                                                                <span id="InputRegNumberError" class="help-block2"><strong></strong></span>
                                                            </div>

                                                            <div class="form-group required col-xs-12 col-sm-6">
                                                                <label>Referens </label>
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


                                                                <h3>Beställning</h3>
                                                                <p>
                                                                    När du gjort en beställning genom vår webbutik <a href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a> eller genom att mejla till {{ App\Setting::getOrderMail() }} bekräftar vi beställningen genom att skicka en orderbekräftelse till den mejladress du angivit. Denna orderbekräftelse är en bekräftelse på att ett avtal om köp mellan dig och Hjulonline. Genomförd beställning innebär också att du godkänner våra köpvillkor och samtycker till att namn- och person- eller organisationsnummer registreras i vårt affärssystem. Vi förbehåller oss rätten att korrigera ordrar om leverans av någon anledning inte kan ske och vi förbehåller oss också rätten att slå ihop dina ordrar och leverera dem i ett paket om du har gjort flera beställningar. Vidare förbehåller vi oss rätten att neka eller annullera en order. Vissa produkter är beställningsvara och för dessa görs beställning via e-post/hemsida. För dessa typer av beställningar gäller särskilda frakt- och leveransvillkor. Hjulonline.se ingår ej avtal med personer som inte är myndiga (18 år). För mer information om hur du handlar i webbutiken, kontakta hjulonline.se
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Ändring av beställning eller avbeställning </b><br>
                                                                    Du kan göra ändringar i din beställning fram till dess att beställningen har effektuerats. Med effektuering menas att ordern har skapats. Om ändringen godkänns kommer eventuella prisdifferenser att regleras enligt det pris som gäller vid ändringstidpunkten. Det är inte möjligt att ändra en beställning när ordern har effektuerats. En avbeställning är giltig först när du mottagit bekräftelse härom från oss. Du kan avbeställa din order utan kostnad för dig fram till dess att beställningen har effektuerats, dvs faktura eller frakthandling har skapats. <b>Efter denna tidpunkt är du skyldig att ta emot leveransen.</b>  Som kund hos oss har du dock alltid 14 dagars ångerrätt enligt vad som sägs under rubriken ”Ångerrätt” nedan. Avbeställning av vara som tillverkats speciellt för dig är inte möjlig efter det att vi beställt eller påbörjat tillverkningen av varan/tjänsten.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Priser och information på vår hemsida  </b><br>
                                                                    Priserna som anges i vår webbutik är inklusive moms om du bor inom EU. Bor du utanför EU ska priserna som visas vara exklusive moms. Vi reserverar oss för eventuella fel i pris- och produktinformation på hemsidan. I händelse av felaktigt pris förbehåller sig hjulonline.se rätten att ändra detta i efterhand. Du som kund kommer självfallet att bli tillfrågad om du vill fullfölja köpet innan vi gör en sådan ändring. Vi reserverar oss också för prisändringar, slutförsäljning, lagerdifferenser, tekniska problem, ändringar av tekniska specifikationer och eventuella typografiska fel på vår sida och förbehåller oss rätten att ändra produktinformation och priser utan föregående avisering. Bilder som förekommer i vår webbutik ska inte ses som exakta avbildningar av en viss vara.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Frakt- & administrationsavgift</b><br>
                                                                    Utöver priset som visas för respektive produkt i vår webbutik kan det tillkomma en frakt- och administrationsavgift. Denna avgift är tänkt att täcka kostnader för hantering, paketering, frakt och emballage. Den aktuella frakt- och administrationsavgiften finns angiven på vår hemsida samt när du befinner dig i kassan. Den ska också alltid framgå på din orderbekräftelse. 
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Personuppgifter </b><br>
                                                                    I samband med att du registrerar dig som kund hos oss eller genom din beställning godkänner du att vi lagrar och använder dina uppgifter i vår verksamhet för att fullfölja och tillhandahålla den service som du kan förvänta dig av oss. Enligt PUL (personuppgiftslagen) har du rätt att få den information som vi har registrerat på dig. Om du anser den vara felaktig eller irrelevant kan du begära rättelse eller borttagning ur vårt kundregister. Kontakta gärna vår kundservice så hjälper vi dig. Informationen du lämnar om dig själv används av Hjulonline och våra samarbetspartners för betalning och leverans. Vi garanterar att inga uppgifter om dig kommer att säljas eller föras vidare till tredje part. Vi förbehåller oss rätten att annullera ordrar som innehåller felaktiga personuppgifter och/eller där kunden har betalningsanmärkningar.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Copyright </b><br>
                                                                    Allt innehåll på <a href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a>, så som text, bilder, filer, logotyper och program tillhör Hjulonline.se eller våra leverantörer och är skyddat av svenska och internationella upphovsrättslagar. För att använda material från vår hemsida krävs ett skriftligt tillstånd från oss.  
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Varor som beställs för användning utanför Sverige </b><br>
                                                                    Hjulonline skickar varor till flera olika länder. Det är du som kund som måste kontrollera om varorna du beställt är godkända i det land de ska användas. Detta kan t ex gälla elektriska produkter, blandare, mm. I olika länder finns ibland olika regler kring vatten och el och det är du som kund som måste kontrollera att produkten som ska installeras uppfyller de olika regler som kan finnas.
                                                                </p>

                                                                 <br>

                                                                <h3>Betalning</h3>
                                                                <p>
                                                                    I kassan kan du välja mellan betalsätten kortbetalning och förskottsbetalning. Nedan kan du läsa mer om våra olika betalsätt.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Kortbetalning – VISA & MasterCard </b><br>
                                                                    Vi samarbetar med DIBS, som är Nordens ledande leverantör av betaltjänster via Internet. DIBS är certifierade av banker och kortinlösare till att hantera betaltransaktioner på högsta säkerhetsnivå. All kommunikation mellan butiken och din bank sköts av DIBS och krypteras via SSL (Secure Sockets Layer). Vi har därmed aldrig tillgång till kortinformationen och kan därför inte registrera eller lagra dina kortuppgifter. Vid betalning med kort fyller du i ditt kortnummer, kortets giltighetstid och CVC/CVV-kod. Vi accepterar betalning med VISA och MasterCard. Observera att pengarna endast reserveras vid beställning. Det är först när vi levererar och skapar kvitto som pengarna dras från ditt konto.  
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Återbetalningar  </b><br>
                                                                    I de fall återbetalningar är aktuella så görs detta i första hand genom återbetalning på samma sätt som den ursprungliga betalningen gjordes. Återbetalningen sker inom några dagar från det att ärendet som orsakar återbetalningen är avslutat.
                                                                </p>

                                                                <br>

                                                                <h3>Frakt & Leverans</h3>
                                                                <p>
                                                                    Den leveranstid som anges i vår webbutik eller i orderbekräftelse är endast en preliminär leveranstid. Har du bråttom med en leverans rekommenderar vi alltid att du mailar vår kundtjänst och stämmer av innan beställning. Kostnaden för frakt- och administration finns specificerad på din orderbekräftelse. Vissa undantag där fraktkostnaden avtalas i efterhand finns gällande vissa beställningsvaror som beställs på telefon eller mejl; se avsnitt nedan gällande "Leverans till öar" och "Vissa skrymmande och tunga varor".  
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Leverans till utlämningsställe  </b><br>
                                                                    Till privatpersoner levereras normalt alla paketförsändelser till ett paketutlämningsställe (DHL Service Point & Postnord) i närheten av din utvalda leveransadress. Mindre företag som ej är på plats hela dagarna väljer också detta fraktalternativ. Du får ett SMS när paketet är framme och går att hämta ut hos ombudet. Du kan sedan hämta ut din vara när det passar dig. Dock måste varan hämtas inom 14 dagar. Varan kan endast hämtas ut av personen som står som leveransmottagare. Det är legitimationskontroll vid uthämtning av varan.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Direktleverans </b><br>
                                                                    För företag med bemanning under normal kontorstid skickas normalt varorna direkt till angiven adress utan avisering. För vissa tyngre och mer skrymmande leveranser skickar vi även direkt hem till privatpersoner. Privatpersoner får alltid en avisering av transportbolaget innan utkörning sker. Varorna skickas då till porten om du bor i lägenhetshus eller till tomtgränsen om du bor i villa. Produkterna levereras under dagtid och kräver att en mottagare kvitterar godset. Vi kan tyvärr inte påverka transportörens tider och kördagar. I vissa mindre orter i Sverige kan det förekomma att transportören enbart har hemleverans vissa dagar i veckan. Vid mottagande av varorna ska de inspekteras noggrant innan leveransen kvitteras. Om det finns synliga skador på emballage eller på varan skall detta noteras på fraktsedeln innan du kvitterar den. Fraktbolaget ställer ej av gods utan kvittens av kund eller för kundens räkning ansvarig mottagare. Detta innebär att du ansvarar för att det finns någon som kan ta emot och kvittera godset när leveransen utförs. 
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Leverans till öar  </b><br>
                                                                    För beställningar genom vår webbutik är det ej möjligt att få leverans till öar utan fast vägförbindelse (Gotland är dock undantaget från denna regel). Har du som kund valt leverans till paketutlämningsställe kommer leveransen att skickas till närmaste utlämningsställe på fastlandet. Om du vill ha leverans till en ö utan vägförbindelse, maila vår kundservice så försöker vi hjälpa till. 
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Vissa skrymmande och tunga varor </b><br>
                                                                    Vissa beställningsprodukter som kan beställas på telefon eller mejl kräver avlossningshjälp från lastbilen. Hjulonline förbehåller sig rätten att vid dessa tillfällen debitera kunden extra kostnader som uppkommer vid avlastning och detta kommer då att faktureras i efterhand. Det är viktigt att du som kund planerar mottagandet av varorna i förväg. Vid frågor gällande fraktkostnad av tungt
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Leveransförseningar </b><br>
                                                                    Observera att leveranstiderna som visas under respektive produkt på vår hemsida och i orderbekräftelser från oss endast är indikationer. Leveranstiderna kan komma att förlängas då oförutsedda förseningar kan uppstå. Vi har nästan alla produkter vi säljer i lager men ibland händer det att varan är slut i vårt lager och även hos vår leverantör. Vår ambition är att alltid informera dig som kund om eventuella leveransförseningar. Vi hjälper dig gärna med förslag på likvärdig produkt om sådan finns. Skulle en leveransförsening uppstå har du som kund rätt att utan kostnad häva köpet. OBS: Detta gäller dock ej produkter som är beställningsvara eller som producerats efter kunds unika önskemål. Till vissa delar av Norrland och till Gotland levererar DHL med en eller ett par dagars fördröjning, denna är inte alltid inräknad i den beräknade leveranstid vi anger på hemsidan. Observera att Hjulonline ej kompenserar för leveransförseningar. Boka aldrig verkstadstid innan ni mottagit och kontrollerat varan. Maila alltid och stäm av med oss innan beställning om ni har bråttom med en leverans. 
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Transportskada  </b><br>
                                                                    Eventuella transportskador som uppstått under transporten till dig står Hjulonline för. Om du vid mottagandet av godset upptäcker att försändelsen är transportskadad, antingen på produkten eller på emballaget, ber vi dig att genast på plats anmäla detta till fraktbolaget genom att notera skadan på fraktsedeln innan leveransen kvitteras. Om du ser eller hör på en gång att en vara är helt förstörd, t ex ett handfat där porslinsskärvor skramlar i kartongen, så ska du inte ta emot varan alls. Be transportören ta tillbaka hela försändelsen. Fotografera alltid synliga transportskador. Stora palleveranser av skrymmande gods rekommenderar vi att du granskar tillsammans med transportören. Skadan skall därefter direkt anmälas till vår kundservice så att vi på bästa sätt kan hjälpa till med din reklamation. Detsamma gäller för eventuella dolda skador som upptäckts på försändelsen. Skadan ska anmälas till oss inom sju (7) arbetsdagar från mottagandet av leveransen. Det är därför viktigt att du packar upp och undersöker dina beställda produkter noggrant direkt efter att leveransen har utförts eller direkt efter att du hämtat paketet hos paketutlämningsstället. Om du inte kontrollerar varorna <b>inom 2 dagar</b> gäller inte transportförsäkringarna och vi kan därmed ej ersätta varorna eftersom transportbolaget ej kommer att ersätta oss.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Delleveranser </b><br>
                                                                    Vid större beställningar kan det förekomma att ordern delas upp i flera leveranser. Vi förstår att detta inte alltid är önskvärt och vi strävar alltid efter att hålla nere antalet delleveranser. 
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Ej uthämtade paket  </b><br>
                                                                    Som köpare ansvarar du för att lösa ut försändelsen på utlämningsstället inom den tid som står angivet på avin. Om du inte kan ta emot paketet vid leverans eller inte hämtar ut ditt paket på utlämningsstället, skickas det tillbaka till oss och vi kan då komma att debitera dig en skälig summa för frakt- och administrativa kostnader. <b>Vi tar ut en avgift om 1500 kr om ett paket ej hämtas ut.</b>
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Ångerrätt och retur </b><br>
                                                                    Enligt Distans- och hemförsäljningslagen har du som konsument alltid 14 dagars ångerrätt. Vid åberopande av ångerrätt står du som kund för returfrakten.  En förutsättning för ångerrätten är dock att varan är oanvänd och oskadad samt att medföljande originalaskar och emballage är helt och rent. Om varan eller originalaskar inte är i oförändrat skick kan vi komma att göra ett prisavdrag som motsvarar varans värdeminskning. Det är viktigt att ni skickar tillbaka varorna i hela originalaskar. Du har inte rätt att ångra dig om varan på grund av sin beskaffenhet inte kan återlämnas. Lag (2005:59) om distansavtal och avtal utanför affärslokaler innehåller ett antal bestämmelser som begränsar rätten att utnyttja ångerrätten. Vi vill uppmärksamma kunden att enligt 5 § gäller inte ångerrätt om avtalet avser: "en vara som skall tillverkas eller väsentligt ändras efter konsumentens särskilda önskemål eller som annars skall få en tydlig personlig prägel". Exempel är sådana varor som tillverkas efter beställning och kundanpassas efter kundens önskemål. Ångerrätten gäller ej heller vid avtal 11 § punkt 4 som avser: "för en vara som snabbt kan försämras eller bli för gammal". Exempel på sådana varor i vårt sortiment är däck och fälgar från utländska märken. Vid utnyttjande av ångerrätt eller retur, mejla oss alltid på {{ App\Setting::getOrderMail() }} 
                                                                    <br>
                                                                    <br>
                                                                    1) Ordernummer (finns i din orderbekräftelse, på följesedeln eller fakturan) 2) Namn och kontaktuppgifter 3) Artikelnummer och antal på de produkter som önskas returneras
                                                                    <br>
                                                                    <br>
                                                                    Du måste få en bekräftelse från oss att returen är godkänd och att ångerfristen inte har passerats innan varorna skickas tillbaka. Returen skall ske med Posten Företagspaket, DHL Företagspaket, Schenker Företagspaket eller med Fedex och du som kund står för returkostnaden. Du kan också fråga oss vad vi skulle ta för en returfrakt. Kunden har ansvaret för varan till dess att den har kommit oss tillhanda. Returer får inte skickas som postförskott eller till paketutlämningsställen.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Återbetalning </b><br>
                                                                    När vi har mottagit och kontrollerat den returnerade varan återbetalar vi så snart vi kan värdet på den returnerade varan, dock <b>senast inom 60 dagar.</b> Eventuell fraktkostnad eller returfraktkostnad <b>återbetalas ej.</b> Vi återbetalar pengarna genom samma kanal som de kom till oss.
                                                                </p>

                                                                <br>

                                                                <h3>Reklamation</h3>
                                                                <p>
                                                                    När vi har mottagit och kontrollerat den returnerade varan återbetalar vi så snart vi kan värdet på den returnerade varan, dock <b>senast inom 60 dagar.</b> Eventuell fraktkostnad eller returfraktkostnad <b>återbetalas ej.</b> Vi återbetalar pengarna genom samma kanal som de kom till oss.
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Återbetalning </b><br>
                                                                    Om du har fått en defekt eller felexpedierad vara ber vi dig att omgående kontakta vår kundservice på {{ App\Setting::getOrderMail() }} . Beskriv felet så utförligt som möjligt, gärna tillsammans med bild. Om vi inte kan lösa problemet får du naturligtvis reklamera produkten för att få en ny. Reklamationer skall göras i enlighet med de instruktioner du får av oss. Vid godkänd reklamation står alltid Hjulonline för ny frakt och eventuell returfrakt. Boka aldrig däcktid förrän ni har kontrollerat att rätt artiklar har levererats och att inga skador har uppstått under leveransen. Felplockade och felaktiga varor får inte monteras, då anses köparen ha godkänt sin leverans. Detta gäller dock inte om omständigheter gör att det först vid montering går att se att varan ifråga är felplockad eller trasig. <b>Reklamation av däck och fälg godkänns ej efter montering eller installation.</b> Så var noggrann att kontrollera dessa produkter innan uppsättning eller montering. Hjulonline ersätter ej för eventuella Verkstadskostnader i samband med en reklamation. Därför är det viktigt att du kontrollerar varorna innan installation. Vid eventuell tvist följer vi <b><a target="_blank" href="http://www.arn.se/">Allmänna reklamationsnämndens rekommendationer.</a></b>
                                                                </p>

                                                                <br>

                                                                <p>
                                                                    <b>Force Majeure</b><br>
                                                                    Både Hjulonline och kund ska vara befriade från fullgörande av köpet om fullgörandet förhindras eller väsentligen försvåras av omständighet som Hjulonline eller kund inte rimligen kunnat råda över eller förutse. Följande och liknande omständigheter skall anses utgöra befrielsegrunder där de hindrar eller försvårar köpets fullgörelse: eldsvåda, krig, mobilisering, rekvisition, beslag, valutarestriktioner, allmän varuknapphet, knapphet på transportmedel, strejk, lockout, inskränkningar i form av drivkraft samt fel i eller försening av leveranser från underleverantörer som har sin grund i sådan omständighet som avses i denna punkt eller andra omständigheter som Hjulonline eller kund ej kan råda över, som antingen hindrar eller försvårar Hjulonlines eller kunds fullgörande i sådan grad, att det ej kan ske annat än till onormalt hög kostnad.
                                                                </p>

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
                                                        <button type="button" id="goNextBtn" name="goNextBtn" class="btn btn-primary btn-lg js-loading-button" data-loading-text="Vänta..." autocomplete="off" style="font-size: 1.4em">Gå vidare &nbsp; <i class="fa fa-arrow-right"></i></button>
                                                    </div>

                                                </form>
                                        </div>
                                        @endif

                                    {{-- </div> col-xs-12 --}}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="">
                                <h4 class="panel-title">
                                    {{-- <a class="collapsed" role="button" data-toggle="collapse"
                                       data-parent="#accordion" href="#PaymentOptions" aria-expanded="false"
                                       aria-controls="PaymentOptions"> --}}
                                        Betalning
                                    {{-- </a> --}}
                                </h4>
                            </div>
                            <div id="PaymentOptions" class="panel-collapse collapse" role="tabpanel" aria-labelledby="PaymentOptions">
                                <div class="panel-body">
                                    <div class="form-group col-lg-12 col-sm-12 col-md-12 -col-xs-12">
                                        {{-- <table style="width:100%" class="table-bordered table tablelook2" id="paymentOptionsTable">
                                        </table> --}}

                                        <div id="paymentOptionsList"></div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12 text-center" id="loadingImageDiv" style="display: none;"><img height="40" class="pull-left" src="{{ asset('images/site/spinner-loading.gif') }}" alt='img'></div>
                                            <div class="col-sm-12 pull-right">

                                           {{--      <button style="margin-left: 5px" type="button" id="forwardPaymentOptions" class="btn btn-primary btn-lg pull-right" title="checkout">Gå vidare &nbsp; <i class="fa fa-arrow-right"></i></button> --}}
                                                <div id="netsPaymentForm" class="row"></div>
                                                <a id="backPaymentOptions" class="btn btn-default btn-lg pull-right" style="font-size: 1.4em">
                                                <i class="fa fa-arrow-left"></i> &nbsp; Tillbaka </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar hidden-xs">
            <div class="contentBox">
                <div class="w100 costDetails">
                    <div class="table-block" id="order-detail-content">

                        <div class="w100 cartMiniTable">
                            <table id="cart-summary" class="std table">
                                <tbody>
                                <tr>
                                    <td>Totalt produkter</td>
                                    <td class="price">{{ $cartCalculator->totalPriceProducts() }} {{ $cartCalculator->getCurrency() }}</td>
                                </tr>
                                <tr style="">
                                    <td>Leverans</td>
                                    <td class="price shippingCost">{{ $cartCalculator->totalPriceShipping() }} {{ $cartCalculator->getCurrency() }}</td>
                                </tr>
                                <tr class="cart-total-price ">
                                    <td>Totalt (ex moms)</td>
                                    <td id="totalPriceExTax" class="price totalPriceExTax">{{ $cartCalculator->totalPriceExTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                </tr>

                                @if(Session::has('campaign'))
                                    <tr>
                                        <td>Moms</td>
                                        <td class="price totalTax" id="totalTax">{{ ($cartCalculator->totalTax() - Session::get('campaign.discount')) * 0.2 }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Rabatt</td>
                                        <td class="" style="color: #D14339;">- {{ Session::get('campaign.discount') }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Totalt</td>
                                        <td class="site-color total-price" id="total-price">{{  $cartCalculator->totalPriceIncTax() - Session::get('campaign.discount') }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Moms</td>
                                        <td class="price totalTax" id="totalTax">{{ $cartCalculator->totalTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Totalt</td>
                                        <td class="site-color total-price" id="total-price">{{  $cartCalculator->totalPriceIncTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    @if ($errors->has('campaignCode'))
                                    <td colspan="2">
                                        <span class="help-block has-error">
                                            <strong>{{ $errors->first('campaignCode') }}</strong>
                                        </span>
                                    </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End popular -->
            <br>
        </div>
        <!--/rightSidebar-->

    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /.main-container -->

<div class="gap"></div>
@endsection

@section('footer')
    {{-- <script src="{{ asset('assets/js/myCustomScripts/cart.js') }}"></script> --}}
    <script src="{{ elixir('js/customScripts/cart.js') }}"></script>
    {{-- <script src="../wz/{{ elixir('js/customScripts/cart.js') }}"></script> --}}

<script>
    $(document).on('click', '#getAddress', function(e) {
        e.preventDefault();
        $('.getAdressMessage').empty();
        $('#ssn').parent().removeClass('has-error');
        $('#ssnError strong').empty();

        // console.log($('input[name="isCompanyCheckout"]:checked').val());
        // console.log($('input[name="isCompanyCheckout"]').iCheck('update')[0].checked);

        $.ajax({
            type: 'GET',
            url: 'getAddress',
            data: {
                ssn : $('#ssn').val(),
                isCompany : $('input[name="isCompanyCheckout"]:checked').val(),
            },
            success: function(data) {
                console.log(data.customerInfo);
                // $('#orderFormFields').empty();
                // $('#orderFormFields').append(data.orderForm);
                if(data.message) {
                    $('.getAdressMessage').append('<div class="alert alert-danger">' + data.message + '</div>')
                }

                $('#InputCompanyName').val(data.customerInfo.companyName);
                $('#InputFirstName').val(data.customerInfo.firstName);
                $('#InputLastName').val(data.customerInfo.lastName);
                $('#InputBillingPhoneNumber').val(data.customerInfo.phone);
                $('#InputBillingAddress').val(data.customerInfo.address);
                $('#InputBillingPostalCode').val(data.customerInfo.postalCode);
                $('#InputBillingCity').val(data.customerInfo.city);
            }
        });
    });

    $(document).on('change', '.alternativeAddress', function(e) {
        e.preventDefault();
        $alternativeAddressId = $(this).val();
        // console.log($('#ssn').val(), $addressInfoId);

        $.ajax({
            type: 'GET',
            url: '../../../getAlternativeAddress',
            data: {
                ssn : $('#ssn').val(),
                alternativeAddressId : $alternativeAddressId,
            },
            success: function(data) {
                // console.log(data.addressInfoInfo);
                $('#orderFormFields #billingStreetAddress').val(data.addressInfo.address);
                $('#orderFormFields #billingPostalCode').val(data.addressInfo.postalCode);
                $('#orderFormFields #billingCity').val(data.addressInfo.city);
            }
        });
    });
    $(document).on('click', '#paymentOptionAnchor', function(e) {
        e.preventDefault(), $('#loadingImageDiv').show();
        var actionUrl = $(this).data("action");
        $.ajax({
            url: actionUrl,
            dataType: 'json',
            success: function(data) {
                $('#loadingImageDiv').hide(), $('#netsPaymentForm').html(data.netsPaymentForm)
            }
        });
    }),
    $(document).on('click', '#goNextBtn', function(e) {
        var btn = $(this).button('loading');
        e.preventDefault();
        $('#message').empty();
        $('#netsPaymentForm').empty();

        var ssnRegEx = new RegExp('^[0-9 -]{10,13}$')
        var nameRegEx = new RegExp('^([A-Za-zÅÄÖåäö0-9 -]){2,}$')
        var addressRegEx = new RegExp('^([A-Za-zÅÄÖåäö0-9 ]){2,}$')
        var postalCodeRegEx = new RegExp('^[0-9 ]+$')
        var cityRegEx = new RegExp('^([A-Za-zÅÄÖåäö0-9 -]){2,}$')
        // var emailRegEx = new RegExp('/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/');
        var emailRegEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var phoneRegEx = new RegExp('^[0-9 -]+$')
        var regNumberRegEx = new RegExp('^([A-Za-z0-9]){6,}$')

        var validated = true;
        var container = $('html, body'),
            scrollTo = $('#orderInformationForm');


        if(!ssnRegEx.test($('#ssn').val())) {
            $('#ssn').parent().addClass('has-error');
            $('#ssnError strong').html('Vänligen ange personnr med 10 siffror');
            validated = false;
        } else {
            $('#ssn').parent().removeClass('has-error');
            $('#ssnError strong').empty();
        }

        if(!nameRegEx.test($('#InputFirstName').val())) {
            $('#InputFirstName').parent().addClass('has-error');
            $('#InputFirstNameError strong').html('Förnamn krävs');
            validated = false;
        } else {
            $('#InputFirstName').parent().removeClass('has-error');
            $('#InputFirstNameError strong').empty();
        }

        if(!nameRegEx.test($('#InputLastName').val())) {
            $('#InputLastName').parent().addClass('has-error');
            $('#InputLastNameError strong').html('Efternamn krävs');
            validated = false;
        } else {
            $('#InputLastName').parent().removeClass('has-error');
            $('#InputLastNameError strong').empty();
        }


        if(!addressRegEx.test($('#InputBillingAddress').val())) {
            $('#InputBillingAddress').parent().addClass('has-error');
            $('#InputBillingAddressError strong').html('Endast siffror är tillåtna');
            validated = false;
        } else {
            $('#InputBillingAddress').parent().removeClass('has-error');
            $('#InputBillingAddressError strong').empty();
        }

        if(!postalCodeRegEx.test($('#InputBillingPostalCode').val())) {
            $('#InputBillingPostalCode').parent().addClass('has-error');
            $('#InputBillingPostalCodeError strong').html('Postnummer krävs');
            validated = false;
        } else {
            $('#InputBillingPostalCode').parent().removeClass('has-error');
            $('#InputBillingPostalCodeError strong').empty();
        }

        if(!cityRegEx.test($('#InputBillingCity').val())) {
            $('#InputBillingCity').parent().addClass('has-error');
            $('#InputBillingCityError strong').html('Stad krävs');
            validated = false;
        } else {
            $('#InputBillingCity').parent().removeClass('has-error');
            $('#InputBillingCityError strong').empty();
        }

        if(!phoneRegEx.test($('#InputBillingPhoneNumber').val())) {
            $('#InputBillingPhoneNumber').parent().addClass('has-error');
            $('#InputBillingPhoneNumberError strong').html('Endast siffror är tillåtna');
            validated = false;
        } else {
            $('#InputBillingPhoneNumber').parent().removeClass('has-error');
            $('#InputBillingPhoneNumberError strong').empty();
        }

        if(!regNumberRegEx.test($('#InputRegNumber').val())) {
            $('#InputRegNumber').parent().addClass('has-error');
            $('#InputRegNumberError strong').html('Ange minst 6 tecken, samt endast siffror & bokstäver är tillåtna');
            validated = false;
        } else {
            $('#InputRegNumber').parent().removeClass('has-error');
            $('#InputRegNumberError strong').empty();
        }

        if(!$('#InputShippingPhoneNumber').is(':disabled')) {
            if(!phoneRegEx.test($('#InputShippingPhoneNumber').val())) {
                $('#InputShippingPhoneNumber').parent().addClass('has-error');
                $('#InputShippingPhoneNumberError strong').html('Endast siffror är tillåtna');
                validated = false;
            } else {
                $('#InputShippingPhoneNumber').parent().removeClass('has-error');
                $('#InputShippingPhoneNumberError strong').empty();
            }
        } else {
            $('#InputShippingPhoneNumber').parent().removeClass('has-error');
            $('#InputShippingPhoneNumberError strong').empty();
        }

        if(!postalCodeRegEx.test($('#InputBillingPostalCode').val())) {
            $('#InputBillingPostalCode').parent().addClass('has-error');
            $('#InputBillingPostalCodeError strong').html('Endast siffror är tillåtna');
            validated = false;
        } else {
            $('#InputBillingPostalCode').parent().removeClass('has-error');
            $('#InputBillingPostalCodeError strong').empty();
        }

        if(!$('#InputShippingPostalCode').is(':disabled')) {
            if(!postalCodeRegEx.test($('#InputShippingPostalCode').val())) {
                $('#InputShippingPostalCode').parent().addClass('has-error');
                $('#InputShippingPostalCodeError strong').html('Endast siffror är tillåtna');
                validated = false;
            } else {
                $('#InputShippingPostalCode').parent().removeClass('has-error');
                $('#InputShippingPostalCodeError strong').empty();
            }
        } else {
            $('#InputShippingPostalCode').parent().removeClass('has-error');
            $('#InputShippingPostalCodeError strong').empty();
        }

        if(!$('#conditions').is(':checked')) {
            $('#conditions').parent().addClass('has-error');
            validated = false;
        } else {
            $('#conditions').parent().removeClass('has-error');
        }

        if ($('#InputEmail').val().length < 4 || !emailRegEx.test($('#InputEmail').val())) {
            $('#InputEmail').parent().addClass('has-error');
            validated = false;
        } else {
            $('#InputEmail').parent().removeClass('has-error');
        }

        if (!validated) { container.animate({scrollTop : scrollTo.offset().top},500),btn.button('reset');}

        if(validated) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: 'saveOrderInfo',
                data: $('#orderInformationForm').serialize(),
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                success: function(data) {
                    // console.log(data.message);
                    // $('#message').append(data.message);
                    if(!data.isValidOrder) {
                        window.location.href = '{{ url('varukorg') }}';
                    }

                    if(data.isAdmin) {
                        window.top.location.href = '{{ url('orderbekraftelse') }}/' + data.token;
                    }else{
                        $('#paymentOptionsList').html(data.paymentMethodOptions);
                        //$('#netsPaymentForm').append(data.netsPaymentForm);
                        $('#CartInformation').collapse('hide')
                        // $('#ShippingOptions').collapse('show')
                        $('#PaymentOptions').collapse('show')
                        $('html, body').animate({
                            scrollTop: $(".onepage-checkout").offset().top
                        }, 1000);
                        btn.button('reset');
                    }
                }
            });
        }
    });

    $(document).on('click', '#forwardShippingOptions', function(e) {
        e.preventDefault();
        $('#message').empty();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

        $.ajax({
            type: 'POST',
            url: 'saveDeliveryInfo',
            data: {
                deliveryId : $('input[name="deliveryMethod"]:checked').val(),
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {

                $('#netsPaymentForm').append(data.netsPaymentForm);
                $('.shippingCost').empty();
                $('.shippingCost').append(data.totalPriceShipping + ' kr');

                $('.totalPriceExTax').empty();
                $('.totalPriceExTax').append(data.totalPriceExTax + ' kr');

                $('.totalTax').empty();
                $('.totalTax').append(data.totalTax + ' kr');

                $('.total-price').empty();
                $('.total-price').append( data.totalPriceIncTax + ' kr');
                // console.log(data.paymentMethodOptions);
                // $('#paymentOptionsList').empty();
                // $('#paymentOptionsList').append( data.paymentMethodOptions);

                $('#ShippingOptions').collapse('hide')
                $('#PaymentOptions').collapse('show')

                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-green iCheck-margin',
                    radioClass: 'iradio_square-green iChk iCheck-margin'
                });

                $('html, body').animate({
                    scrollTop: $(".onepage-checkout").offset().top
                }, 1000);


            }
        });
    });

    $(document).on('click', '#backShippingOptions', function(e) {
        e.preventDefault();
        $('#CartInformation').collapse('show')
        $('#ShippingOptions').collapse('hide')

        $('html, body').animate({
            scrollTop: $(".onepage-checkout").offset().top
        }, 1000);
    })

    $(document).on('click', '#forwardPaymentOptions', function(e) {
        e.preventDefault();
        $('#message').empty();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

        $.ajax({
            type: 'POST',
            url: 'savePaymentInfo',
            data: {
                paymentId : $('input[name="paymentMethod"]:checked').val(),
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                if(data.klarnaForm) {
                    $('#klarnaForm').append(data.klarnaForm);
                    $('#storeOrderForm').addClass('hidden');
                }

                if(data.orderForm) {
                    $('#klarnaForm').empty();
                    $('#storeOrderForm').removeClass('hidden');
                }

                $('#PaymentOptions').collapse('hide')
                $('#OrderPayment').collapse('show')
                $('html, body').animate({
                    scrollTop: $(".onepage-checkout").offset().top
                }, 1000);
            }
        });
    });


    $(document).on('click', '#backPaymentOptions', function(e) {
        e.preventDefault();
        $('#CartInformation').collapse('show')
        // $('#ShippingOptions').collapse('show')
        $('#PaymentOptions').collapse('hide')
        $('html, body').animate({
            scrollTop: $(".onepage-checkout").offset().top
        }, 1000);
    })

    $(document).on('click', '#backOrderPayment', function(e) {
        e.preventDefault();
        $('#PaymentOptions').collapse('show')
        $('#OrderPayment').collapse('hide')
        $('html, body').animate({
            scrollTop: $(".onepage-checkout").offset().top
        }, 1000);
    })



    $(document).ready(function() {
        $('#loading-image-register').hide();
    });

    $(document).ajaxStart(function(){
        $('#loading-image-register').show();
    }).ajaxStop(function(){
        $('#loading-image-register').hide();
    });

    $(document).on('ifChanged', '#isOrderShippingAddress', function() {
        if($(this).prop("checked")) {
            $('.orderShippingAdressForm').removeClass('hidden');
            $('.orderShippingAdressForm [name="fullName"]').prop( "disabled", false );
            $('.orderShippingAdressForm [name="shippingStreetAddress"]').prop( "disabled", false );
            $('.orderShippingAdressForm [name="shippingPostalCode"]').prop( "disabled", false );
            $('.orderShippingAdressForm [name="shippingCity"]').prop( "disabled", false );
            $('.orderShippingAdressForm [name="shippingPhone"]').prop( "disabled", false );

        } else {
            $('.orderShippingAdressForm').addClass('hidden');
            $('.orderShippingAdressForm [name="fullName"]').prop( "disabled", true );
            $('.orderShippingAdressForm [name="shippingStreetAddress"]').prop( "disabled", true );
            $('.orderShippingAdressForm [name="shippingPostalCode"]').prop( "disabled", true );
            $('.orderShippingAdressForm [name="shippingCity"]').prop( "disabled", true );
            $('.orderShippingAdressForm [name="shippingPhone"]').prop( "disabled", true );
        }
    });

    //Register user company/private
    //

    $(document).on('change', '#InputBillingCountry', function() {
        // console.log($(this).val());
        if($(this).val() != 'SE') {
            $('.input-group-btn').addClass('hidden');
            $('.input-group-btn').parent().removeClass('input-group');
        } else {
            $('.input-group-btn').removeClass('hidden');
            $('.input-group-btn').parent().addClass('input-group');
        }
    })

    $(document).on('ifChecked', '#company', function() {
        $('#InputCompanyName').parent().removeClass('hidden');
        $('#InputCompanyName').attr('required', true);
        $('#InputCompanyName').attr('disabled', false);
    });

    $(document).on('ifChecked', '#private', function() {
        $('#InputCompanyName').parent().addClass('hidden');
        $('#InputCompanyName').attr('required', false);
        $('#InputCompanyName').attr('disabled', true);
    });

    $(document).ready(function() {
        $('#autofillShipping').on('ifChecked', function() {
            $('#deliveryAdress').removeClass('hidden');
            // $('#InputCompanyName').attr('required', true);
            // $('#InputCompanyName').attr('disabled', false);

            $('#InputShippingFullName').attr('required', true).attr('disabled', false);
            $('#InputShippingPhoneNumber').attr('required', true).attr('disabled', false);
            $('#InputShippingAddress').attr('required', true).attr('disabled', false);
            $('#InputShippingPostalCode').attr('required', true).attr('disabled', false);
            $('#InputShippingCity').attr('required', true).attr('disabled', false);
            // $('#InputShippingCountry').attr('required', true).attr('disabled', false);
        });

        $('#autofillShipping').on('ifUnchecked', function() {
            $('#deliveryAdress').addClass('hidden');
            // $('#InputCompanyName').attr('required', false);
            // $('#InputCompanyName').attr('disabled', true);

            $('#InputShippingFullName').attr('required', false).attr('disabled', true);
            $('#InputShippingPhoneNumber').attr('required', false).attr('disabled', true);
            $('#InputShippingAddress').attr('required', false).attr('disabled', true);
            $('#InputShippingPostalCode').attr('required', false).attr('disabled', true);
            $('#InputShippingCity').attr('required', false).attr('disabled', true);
            // $('#InputShippingCountry').attr('required', false).attr('disabled', true);
        });
    });
</script>

@endsection