<style>
	.left {
		width: 45%;
		float:left;
		padding-right: 5%;
	}

	.right {
		width: 45%;
		float:right;
		padding-left: 5%;
	}

	.order-box-content {
		border-top:1px solid #333;
	}

	.pull-right {
		float: right;
		padding-right: 10px
	}

</style>
<div class="row">
    <div class="col-sm-12 ">
        <div class="row userInfo">

            <div class="thanxContent">

                <h1> Din order är under behandling</h1>

            </div>

            <div class="statusContent">


                    <div class="col-sm-12">
                        <div class=" statusTop">
                            <p><strong>Order Nummer:</strong> #{{ $order->id }} </p>
                            <p><strong>Order Datum:</strong> {{ $order->created_at }}</p>
                            <p><strong>Status: <span style="background-color: #f0ad4e; color: #fff; padding: 5px;">Behandlas</span></strong></p>
                            <p><strong>Leveranstid: </strong>  {{ $order->delivery_time }}</p>


                        </div>
                    </div>
                    <br>
                    {{-- <div class="left">
                        <div class="order-box"> --}}
                            {{-- <div class="order-box-header"> --}}
                                {{-- <h3>{{ $order->payment_type !== "Kort" ? "Faktura Adress" : "Kortbetalning genomförd"}}</h3> --}}
                            {{-- </div> --}}


                          {{--   <div class="order-box-content">
                                <div class="address">
                                @if($order->payment_type !== "Kort")
                                    <p><strong>{{ $order->billing_full_name}} </strong></p>

                                    <div class="adr">
                                        {{ $order->billing_street_address }}<br>
                                        {{ $order->billing_postal_code }} {{ $order->billing_city }}<br>
                                        {{ strtoupper($order->billing_country) }}<br>
                                    </div>
                                </div>
                                @else
                                    <div class="adr" style="min-height: 95px; padding-top: 10px;">
                                        Kort typ: {{ $order->card_type }}<br>
                                        Kortnummer: {{ $order->masked_card_number }} <br>
                                    </div>
                                </div>
                                @endif --}}
                       {{--  </div>
                    </div> --}}

					<h3>{{ $order->payment_type !== "Kort" ? "Faktura Adress" : "Kortbetalning genomförd"}}</h3>
                    <div class="left">
                        <div class="order-box">
                            <div class="order-box-header">
                                <h3>Leverans Adress</h3>
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

                    <br>
                    <br>

            <div class="col-sm-10">
				<h3>Beställda produkter</h3>
	            <div class="order-box-content">
	            	 
	                <div class="">
	                    <table class="" cellspacing="0" width="100%">
	                        <tbody>

	                        {{-- <tr class="CartProduct cartTableHeader">
	                            <td colspan="4"> <h3>Beställda produkter</h3> </td>
	                        </tr> --}}

	                        @foreach($order->orderDetails as $orderItem)

	                            <tr class="">
	                                <td class="">

	                                    <div>
	                                        @if($orderItem->product->product_type_id <= 10)
	                                            <a href="{{ url($orderItem->product->productType->name .'/'. $orderItem->product_id) }}"> 
	                                        @endif
	                                        @if (isset($orderItem->product->productImages->first()->name))
	                                           <img height="100" src="{{ $message->embed($orderItem->product->productImages->first()->thumbnail_path) }}" alt="img">
	                                        @elseif($orderItem->product->productType->name == 'falgar')
	                                                <img height="100" src="{{ $message->embed('images/product/noRimImg.jpg') }}" alt="img">
	                                        @else
	                                            <img height="100"  src="{{ $message->embed('images/product/noImg.jpg') }}" alt="img">
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
	                                         {{-- <h4><a href="{{ url($orderItem->product->productType->name .'/'. $orderItem->product_id) }}">{{$orderItem->product->product_name}} </a>

	                                         <div class="price"><span>{{ $orderItem->unit_price }} kr</span></div> --}}
	                                    </div>
	                                </td>
	                                <td class=""><a> x{{ $orderItem->quantity }} </a></td>
	                                <td class=""><span>{{ $orderItem->total_price_including_tax }} {{ $order->currency_notation}}</span></td>
	                            </tr>

	                        @endforeach
	                        <tr class="cartTotalTr blank">
	                            <td class=""></td>
	                            <td></td>
	                            <td class=""></td>
	                            <td class=""><span>  </span></td>

	                        </tr>

	                        <tr class="cartTotalTr">
	                            <td class=""></td>
	                            <td></td>
	                            <td class="pull-right"><b>Totalt produkter: <b></td>
	                            <td class=""><span> {{ $order->totalPriceProducts() }} {{ $order->currency_notation}}</span></td>

	                        </tr>
	                        <tr class="cartTotalTr">
	                            <td class=""></td>
	                            <td></td>
	                            <td class="pull-right"><b>Frakt: <b></td>
	                            <td class=""><span> {{ $order->shipping_fee }} {{ $order->currency_notation}}</span></td>

	                        </tr>
	                        <tr class="cartTotalTr">
	                            <td class=""></td>
	                            <td></td>
	                            <td class="pull-right"><b>Totalt (exkl moms.): </b></td>
	                            <td class=""><span> {{ $order->total_price_excluding_tax }} {{ $order->currency_notation}}</span></td>

	                        </tr>
	                        @if($order->discount)
                            <tr class="cartTotalTr">
	                            <td class=""></td>
	                            <td></td>
	                            <td class="pull-right"><b>Rabatt: </b></td>
	                            <td class=""><span class="price">{{ $order->discount }} {{ $order->currency_notation}}</span></td>

	                        </tr>
                            @endif
	                        <tr class="cartTotalTr">
	                            <td class=""></td>
	                            <td></td>
	                            <td class="pull-right"><b>Totalt: </b></td>
	                            <td class=""><span class="price">{{ $order->total_price_including_tax }} {{ $order->currency_notation}}</span></td>

	                        </tr>

	                        </tbody>
	                    </table>
	                </div> 

	            </div>
        	</div>
        <!--/row end-->
        	<div class="thanxContent">
        		<br>
        		<br>
                
	            <p>
	                Du kan när som helst kolla upp order-detaljerna genom att logga in på <a href="{{url('/login')}}">{{url('/login')}}</a>. 
	                <br>
	                <br>
				</p>

				<br>

        		<hr>
        		<p>
					Med Vänliga Hälsningar
					<br>
					{{env('APP_NAME')}}<br>
				</p>

				<table cellpadding="0" cellspacing="0" border="0" style="background: none; border-width: 0px; border: 0px; margin: 0; padding: 0;">
				<tr><td valign="top" style="padding-top: 0; padding-bottom: 0; padding-left: 0; padding-right: 7px; border-top: 0; border-bottom: 0: border-left: 0; border-right: solid 3px #F7751F"><img id="preview-image-url" src="https://www.hjulonline.se/images/hjulonline_logo2.png"></td>
				<td style="padding-top: 0; padding-bottom: 0; padding-left: 12px; padding-right: 0;">
				<table cellpadding="0" cellspacing="0" border="0" style="background: none; border-width: 0px; border: 0px; margin: 0; padding: 0;">
				<tr><td colspan="2" style="padding-bottom: 5px; color: #F7751F; font-size: 18px; font-family: Arial, Helvetica, sans-serif;">Hjulonline Kundtjänst</td></tr>
				<tr><td colspan="2" style="color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>{{env('APP_NAME')}}</strong></td></tr>
				<tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">m:</td><td style="color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">{{App\Setting::getPhone()}}</td></tr>
				<tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">a:</td><td valign="top" style="vertical-align: top; color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">{{App\Setting::getStreetAddress()}}, {{App\Setting::getPostalCode()}} {{App\Setting::getCity()}}</td></tr>
				<tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">w:</td><td valign="top" style="vertical-align: top; color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><a href="http://www.hjulonline.se" style=" color: #1da1db; text-decoration: none; font-weight: normal; font-size: 14px;">{{env('APP_URL')}}</a>&nbsp;&nbsp;<span style="color: #F7751F;">e:&nbsp;</span><a href="mailto:{{ App\Setting::getOrderMail() }}" style="color: #1da1db; text-decoration: none; font-weight: normal; font-size: 14px;">{{ App\Setting::getOrderMail() }}</a></td></tr>
				</table>
				</td></tr></table>
            </div>

    	</div>

    <!--/rightSidebar-->
	</div>
</div>
