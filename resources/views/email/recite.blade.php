<style>
	* {
	    box-sizing: border-box;
	}
	.header {
	    border: 1px solid red;
	    padding: 15px;
	}
	.row {
		padding: 15px;
	}
	.row::after {
	    content: "";
	    clear: both;
	    display: table;
	}
	[class*="col-"] {
	    float: left;
	    /*padding: 15px;*/
	    /*border: 1px solid red;*/
	}
	.col-1 {width: 8.33%;}
	.col-2 {width: 16.66%;}
	.col-3 {width: 25%;}
	.col-4 {width: 33.33%;}
	.col-5 {width: 41.66%;}
	.col-6 {width: 50%;}
	.col-7 {width: 58.33%;}
	.col-8 {width: 66.66%;}
	.col-9 {width: 75%;}
	.col-10 {width: 83.33%;}
	.col-11 {width: 91.66%;}
	.col-12 {width: 100%;}

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
		border-bottom:1px solid #333;
		padding-bottom: 10px;
	}

	.pull-right {
		float: right;
		padding-right: 10px
	}

	.text-right {
		text-align: right;
	}

	th {
		border-bottom: 1px solid #555;
	}

	.footer {
		border-top: 1px solid #333;
	}

	.footer div {
        /*border-top: 1px solid #333;*/
        padding-right: 5px; 
        padding-top: 20px; 
        margin-top: 40px;
        width: 30%; 
        float: left;
    }







</style>
<div class="row">
    <div class="col-12">
        <div class="row">
			
			<div class="col-8">
				<img src="https://www.hjulonline.se/images/hjulonline_logo2.png"></td>
			</div>

			<div class="col-4">
				<h1 style="margin-bottom: -5px" >Kontant kvitto</h1>
				<p class="col-3">
					<b>Datum</b><br>
					{{ Carbon\Carbon::instance($order->created_at)->toDateString() }} <br>
				</p>

				<p class="col-3">
					<b>Kvittonummer</b><br>
					51 {{ $order->id }}
				</p>
			</div>

		</div>

		<div class="row">
			
			<div class="col-8">
				<p>
					<b>Leveransadress:</b> 
					{{ $order->shipping_full_name }},
					{{ $order->shipping_street_address }},
                    {{ $order->shipping_postal_code }} {{ $order->shipping_city }},
                    {{ App\Http\Utilities\Country::find($order->shipping_country) }}.
                    <br>
                    <b>Ordernummer:</b> {{ $order->id}} 
				</p>
			</div>

			<div class="col-4">
                <div class="address">
                	{{ $order->billing_full_name }} <br>
                    {{ $order->billing_street_address }}<br>
                    {{ $order->billing_postal_code }} {{ $order->billing_city }}<br>
                    {{ App\Http\Utilities\Country::find($order->billing_country) }}<br>
                </div>
            </div>

		</div>

		<div class="row">
            <div class="col-10">
				<h4>Beställda produkter</h4>
	            <div class="order-box-content">
	            	 
	                <div class="">
	                    <table class="" cellspacing="0" width="100%">
	                        <thead>
		                        <tr>
		                            <th> Artnr </th>
		                            <th> Produkt </th>
		                            <th> Antal </th>
		                            <th> Pris </th>
		                            <th> Summa </th>
		                        </tr>
		                    </thead>
		                    <tbody>
	                        @foreach($order->orderDetails as $orderItem)

	                            <tr>
	                                <td>
										{{ $orderItem->product->main_supplier_id }}-{{ $orderItem->product->main_supplier_product_id }}
	                                </td>
	                                <td>
	                                    <div>
	                                    	<b>
                                                @if($orderItem->product->product_type_id <= 10)
                                                    <a href="{{ url($orderItem->product->productType->name .'/'. $orderItem->product_id) }}">
                                                        {{$orderItem->product->product_name}} 
                                                     </a>
                                                @else
                                                    {{$orderItem->product->product_name}} 
                                                @endif
                                             </b>
	                                    </div>
	                                </td>
	                                <td style="text-align: center"><span>{{ $orderItem->quantity }} </span></td>
	                                <td style="text-align: center"><span>{{ $orderItem->unit_price }}  {{ $order->currency_notation}}</span></td>
	                                <td class="text-right" ><span>{{ $orderItem->total_price_including_tax }} {{ $order->currency_notation}}</span></td>
	                            </tr>


	                        @endforeach
	                        </tbody>
							<tfoot>
								<tr><td style="padding-bottom: 20px;"></td></tr>
							
		                        <tr class="cartTotalTr blank">
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td><span>  </span></td>

		                        </tr>

		                        <tr class="cartTotalTr">
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td class="pull-right"><b>Totalt produkter: <b></td>
		                            <td class="text-right"><span> {{ $order->totalPriceProducts() }} {{ $order->currency_notation}}</span></td>

		                        </tr>
		                        <tr class="cartTotalTr">
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td class="pull-right"><b>Frakt: <b></td>
		                            <td class="text-right"><span> {{ $order->shipping_fee }} {{ $order->currency_notation}}</span></td>

		                        </tr>
		                        <tr class="cartTotalTr">
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td class="pull-right"><b>Totalt (exkl moms.): </b></td>
		                            <td class="text-right"><span> {{ $order->total_price_excluding_tax }} {{ $order->currency_notation}}</span></td>

		                        </tr>
		                        @if($order->discount)
	                            <tr class="cartTotalTr">
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td class="pull-right"><b>Rabatt: </b></td>
		                            <td class="text-right"><span class="price">{{ $order->discount }} {{ $order->currency_notation}}</span></td>

		                        </tr>
	                            @endif
		                        <tr class="cartTotalTr">
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td class="pull-right"><b>Totalt: </b></td>
		                            <td class="text-right"><span class="price">{{ $order->total_price_including_tax }} {{ $order->currency_notation}}</span></td>

		                        </tr>

	                        </tfoot>
	                    </table>
	                </div> 

	            </div>
        	</div>
        </div>
        
		<div class="row">
	    	<footer class="footer col-10">
			    <div>
			        <b>Betalningsmottagare</b><br>
			        {{ env('APP_NAME') }}<br>
			        <span style="font-size: 0.95em">{{ App\Setting::getStreetAddress() }}</span> <br>
			        {{ App\Setting::getPostalCode() }}, {{ App\Setting::getCity() }}<br>
			        {{ env('APP_URL') }}<br>
			    </div>
			    <div>
			        <b>Telefon</b><br>
			        {{ App\Setting::getPhone() }}<br><br>
			        <b>E-post</b><br>
			        {{ App\Setting::getSupportMail() }}
			    </div>
{{-- 			    <div>
			        <b>Betalningsuppgifter</b><br>
			        Bankgiro: {{ App\Setting::getBankGiro() }} <br>
			        Säte: {{ App\Setting::getCity() }}
			    </div>
			    <div>
			        <b>Organisations.nr</b><br>
			        {{ App\Setting::getOrgNumber() }} <br>
			        <b>Momsreg.nr VAT</b><br>
			        {{ App\Setting::getVATNumber() }}<br><br>
			        <b>Godkänd för F-skatt</b>

			    </div> --}}

			</footer>
		</div>

    <!--/rightSidebar-->
	</div>
</div>
