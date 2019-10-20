@extends('layout')

@section('content')


<div class="container main-container containerOffset">

    <!--Ad tracktion-->

    <?php
        // dd($adTracktion);
        if (isset($adTracktion)) {
            if ($adTracktion['rimQty'] >= 4 && $adTracktion['tireQty'] >= 4) {
                echo '<img src="https://track.double.net/track/5914/?hash=43babe" width="0" height="0">';
                // echo " <br>Komplete";
            }
            elseif ($adTracktion['rimQty'] || $adTracktion['tireQty']) {
                if ($adTracktion['tireQty'] > $adTracktion['rimQty'] ) {
                    echo '<img src="https://track.double.net/track/5920/?hash=43babe" width="0" height="0">';
                    // echo " <br>tires";
                }
                else {
                    echo '<img src="https://track.double.net/track/5921/?hash=43babe" width="0" height="0">';
                    // echo " <br>Rims";
                }
            }
            
            $cookie = json_decode(Cookie::get('cookie'), true);
            unset($cookie['adTracktion']);
            $cookie = json_encode($cookie);
            Cookie::queue('cookie', $cookie, 60*24*7);
        }
    ?>

    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li class="active"> Order bekräftelse</li>
            </ul>
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-sm-12 ">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">

                <div class="thanxContent text-center">

                    <h1> Tack för din order. Ditt ordernr är # {{ $order->id}}</h1>
                    <p style="color: #D74D42">Preliminärt är det  {{ $order->delivery_time ? $order->delivery_time : '5-7 dagars leveranstid'}} på dina varor</p>
                    <h4> Vi skickar en orderbekräftelse till din mail, kolla även din spamfoldern. </h4>
                    {{-- <h4>vi meddelar när dina varor är på väg</h4> --}}

                </div>


                <div class="order-box-content col-sm-8 col-sm-offset-2">
                    <div class="">
                        <table class="">
                            <tbody>

                            <tr class="CartProduct  cartTableHeader">
                                <td colspan="4"> Varor som har beställts </td>
                                {{-- <td style="width:5%"></td> --}}
                            </tr>

                            @foreach($order->orderDetails as $orderItem)

                                <tr class="">
                                    <td class="">

                                        <div>
                                            @if($orderItem->product->product_type_id <= 10)
                                                <a href="{{ url($orderItem->product->productType->name .'/'. $orderItem->product_id) }}"> 
                                            @endif
                                            @if (isset($orderItem->product->productImages->first()->name))
                                               <img height="100" src="{{ asset($orderItem->product->productImages->first()->thumbnail_path)}}" alt="img">
                                            @elseif($orderItem->product->productType->name == 'falgar')
                                                    <img height="100" src="{{ asset('images/product/noRimImg.jpg') }}" alt="img">
                                            @else
                                                <img height="100"  src="{{ asset('images/product/noImg.jpg') }}" alt="img">
                                            @endif
                                            </a>
                                        </div>
                                    </td>
                                    <td>
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
                                    <td class=""><a> x{{ $orderItem->quantity }} </a></td>
                                    <td class="pull-right" style="padding-top:44px"><span>{{ $orderItem->total_price_including_tax }} {{ $order->currency_notation}}</span></td>
                                </tr>

                            @endforeach
                            <tr class="cartTotalTr blank">
                                <td class="">
                                </td>
                                <td></td>
                                <td class=""></td>
                                <td class=""><span>  </span></td>

                            </tr>

                            <tr class="cartTotalTr">
                                <td class="">
                                    <div></div>
                                </td>
                                <td colspan="2">Totalt produkter</td>
                                <td class=""><span> {{ $order->totalPriceProducts() }} {{ $order->currency_notation}}</span></td>

                            </tr>
                            <tr class="cartTotalTr">
                                <td class="">
                                    <div></div>
                                </td>
                                <td colspan="2">Frakt</td>
                                <td class=""><span> {{ $order->shipping_fee }} {{ $order->currency_notation}}</span></td>

                            </tr>
                            <tr class="cartTotalTr">
                                <td class=""></td>
                                <td colspan="2">Totalt (exkl moms.)</td>
                                <td class=""><span> {{ $order->total_price_excluding_tax }} {{ $order->currency_notation}}</span></td>

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
                                <td class=""></td>
                                <td></td>
                                <td class="">Totalt</td>
                                <td class=""><span class="price">{{ $order->total_price_including_tax }} {{ $order->currency_notation}}</span></td>

                            </tr>

                            </tbody>
                        </table>
                    </div> 

                </div>
            </div>
            <!--/row end-->

        </div>

        <!--/rightSidebar-->
    </div>
    </div>
    <!--/row-->
</div>
    <div style="clear:both"></div>
</div>
<!-- /.main-container -->

<div class="gap"></div>
<div class="gap"></div>
@endsection