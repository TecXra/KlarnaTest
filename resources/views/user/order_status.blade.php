@extends('layout')

@section('header')
    <link href="assets/css/footable-0.1.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/footable.sortable-0.1.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('konto') }}"> Mitt Konto</a></li>
                <li><a href="{{ url('orderlista') }}"> Order Lista</a></li>
                <li class="active"> Order Status</li>
            </ul>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="fa fa-list-alt"></i> Order Status </span></h1>

            <div class="row userInfo">
                <div class="col-lg-12">
                    <h2 class="block-title-2"> Din Order Status </h2>
                </div>


                <div class="statusContent">


                    <div class="col-sm-12">
                        <div class=" statusTop">
                            <p><strong>Status:</strong> {{ $order->orderStatus->label }}</p>

                            <p><strong>Order Datum:</strong> {{ $order->created_at }}</p>

                            <p><strong>Order Nummer:</strong> #{{ $order->id }} </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="order-box">
                            <div class="order-box-header">

                                {{ $order->payment_type !== "Kort" ? "Faktura Adress" : "Genomförd kortbetalning"}}
                            </div>


                            <div class="order-box-content">
                                <div class="address">
                                    {{-- <p><strong>TITEL </strong></p> --}}
                                @if($order->payment_type !== "Kort")
                                    <p><strong>{{ $order->billing_full_name}} </strong></p>

                                    <div class="adr">
                                        {{ $order->billing_street_address }}<br>
                                        {{ $order->billing_postal_code }} {{ $order->billing_city }}<br>
                                        {{ strtoupper($order->billing_country) }}<br>
                                    </div>
                                @else
                                    <div class="adr" style="min-height: 95px">
                                        Kort typ: {{ $order->card_type }}<br>
                                        Kortnummer: {{ $order->masked_card_number }} <br>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-sm-6">
                        <div class="order-box">
                            <div class="order-box-header">

                                Leverans Adress
                            </div>


                            <div class="order-box-content">


                                <div class="address">
                                    {{-- <p><strong>TITEL</strong></p> --}}

                                    <p><strong>{{ $order->shipping_full_name }} </strong></p>

                                    <div class="adr">
                                        {{ $order->shipping_street_address }}<br>
                                        {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
                                        {{ strtoupper($order->shipping_country) }}<br>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>

                    <div style="clear: both"></div>

                   {{--  <div class="col-sm-6">
                        <div class="order-box">
                            <div class="order-box-header">

                                Betalningsmetod
                            </div>


                            <div class="order-box-content">
                                <div class="address">
                                    <p>Master Card <span style="color: green"
                                                                     class="green"> <strong>(Betald)</strong> </span></p>

                                    <p><strong>Korthavare </strong> Erik Gustavsson </p>

                                    <p><strong>Kort Nummer: </strong> 00335 251 124 </p>

                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-sm-6">
                        <div class="order-box">
                            <div class="order-box-header">

                                Leveransmetod
                            </div>


                            <div class="order-box-content">
                                <div class="address">
                                    <p> DHL <a title="tracking number" href="#">#4502</a></p>

                                    <p><strong>Erik Gustavsson </strong></p>

                                    <div class="adr">
                                        4894 Min gata<br>123 45, Stockholm
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
 --}}

                    <div class="col-sm-12 clearfix">
                        <div class="order-box">
                            <div class="order-box-header">

                                Order Artiklar
                            </div>


                            <div class="order-box-content">
                                <div class="table-responsive">
                                    <table class="">
                                        <tbody>
                                        @foreach ($order->orderDetails as $orderItem)
                                            <tr class="cartProduct">
                                                <td class="cartProductThumb" style="width:20%">
                                                    <div>
                                                    @if($orderItem->product->product_type_id <= 10)
                                                        <a href="{{ url($orderItem->product->productType->name .'/'. $orderItem->product_id) }}"> 
                                                    @endif
                                                        @if (isset($orderItem->product->productImages->first()->name))
                                                           <img height="100" src="{{ asset($orderItem->product->productImages->first()->path)}}" alt="img">
                                                        @elseif($orderItem->product->productType->name == 'falgar')
                                                                <img height="100" src="{{ asset('images/product/noRimImg.jpg') }}" alt="img">
                                                        @else
                                                            <img height="100" src="{{ asset('images/product/noImg.jpg') }}" alt="img">
                                                        @endif
                                                    </a></div>
                                                </td>
                                                <td style="width:60%">
                                                    <div class="miniCartDescription">
                                                         <h4>
                                                            @if($orderItem->product->product_type_id <= 10)
                                                                <a href="{{ url($orderItem->product->productType->name .'/'. $orderItem->product_id) }}">
                                                                    {{$orderItem->product->product_name}} 
                                                                 </a>
                                                            @else
                                                                {{$orderItem->product->product_name}} 
                                                            @endif


                                                             <div class="price"><span>{{ $orderItem->unit_price }}  {{ $order->currency_notation}}</span></div>
                                                         </h4>
                                                    </div>
                                                </td>
                                                <td class="" style="width:10%"><a> x{{ $orderItem->quantity }} </a></td>
                                                <td class="" style="width:15%;padding-left: 4%"><span> {{ $orderItem->total_price_including_tax }} {{ $order->currency_notation}} </span></td>

                                            </tr>
                                        @endforeach
                                        
                                        <tr class="cartTotalTr blank">
                                            <td class="" >
                                            
                                            </td>
                                            <td ></td>
                                            <td class="" ></td>
                                            <td class="" ><span>  </span></td>

                                        </tr>

                                        <tr class="cartTotalTr">
                                            <td class="">
                                            
                                            </td>
                                            <td colspan="2">Totalt produkter</td>
                                            <td class=""><span> {{ $order->totalPriceProducts() }} {{ $order->currency_notation}} </span></td>

                                        </tr>
                                        <tr class="cartTotalTr">
                                            <td class="">
                                            
                                            </td>
                                            <td colspan="2">Frakt</td>
                                            <td class=""><span> {{ $order->shipping_fee }} {{ $order->currency_notation}} </span></td>

                                        </tr>
                                        <tr class="cartTotalTr">
                                            <td class="">
                                            
                                            </td>
                                            <td colspan="2">Totalt (exkl moms.)</td>
                                            <td class=""><span> {{ $order->total_price_excluding_tax }} {{ $order->currency_notation}} </span></td>

                                        </tr>
                                        @if($order->discount)
                                        <tr class="cartTotalTr">
                                            <td class=""></td>
                                            <td></td>
                                            <td class="">Rabatt</td>
                                            <td class=""><span class="price">-{{ $order->discount }} {{ $order->currency_notation}}</span></td>

                                        </tr>
                                        @endif
                                        <tr class="cartTotalTr">
                                            <td class="">
                                            
                                            </td>
                                            <td></td>
                                            <td class="">Totalt</td>
                                            <td class=""><span class="price">{{ $order->total_price_including_tax }} {{ $order->currency_notation}} </span></td>

                                        </tr>


                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>


                </div>


                <div class="col-lg-12 clearfix">
                    <ul class="pager">
                        {{-- <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Go to Shop </a>
                        </li> --}}
                        <li class="next pull-left"><a href="{{ url('konto') }}"> ← Tillbaka till Mitt Konto</a></li>
                    </ul>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /main-container -->
@endsection

@section('footer')
<script src="assets/js/footable.js" type="text/javascript"></script>
<script src="assets/js/footable.sortable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.footable').footable();
    });
</script>
@endsection