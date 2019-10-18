<style>
	table, td, th {
	    border: 1px solid #ddd;
	    text-align: left;
	}

	table {
	    border-collapse: collapse;
	    width: 100%;
	}

	th, td {
	    padding: 15px;
	}
</style>
<div class="row">
    <div class="col-sm-12 ">
        <div class="row userInfo">

            <div class="thanxContent">

                <h1>Beställning från {{env('APP_NAME')}}</h1>
                <h4>Ordernummer: # {{$order['order_id']}}</h4>
	            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
		            
		            <thead >
		            	<tr class="CartProduct cartTableHeader">
		            		<th style="width:15%">Leverantör</th>			            		
		            		<th style="width:15%">Artikelnummer</th>			            		
		            		<th style="width:50%">Produkt</th>			            		
		            		<th style="width:10%">Kvantitet</th>			            		
		            		<th style="width:10%">Nettoprs</th>			            		
		            	</tr>
		            </thead>
		            <tbody>
		            @foreach($order['items'] as $item)
		            	<tr>
		            		<td>{{$item['supplier']}}</td>
		            		<td>{{$item['article_number']}}</td>
		            		<td>{{$item['product_code']}}</td>
		            		<td>x{{$item['quantity']}}</td>
		            		<td>{{$item['net_price']}} kr</td>
		            	</tr>
		            @endforeach
		            </tbody>
					
				</table>

				<br>

				<p>
					<u>Meddelande:</u> <br>
					{!! !empty($order['description']) ? $order['description']."<br><br>" : "" !!}

					{!! isset($order['shipLater']) ? "<b>Invänta fler ordrar med däck!<b>" : "" !!}
				</p>

				<br>
				<br>
				<p>Mvh<br>{{ env('APP_NAME') }}</p>

            </div>

    	</div>
	</div>
</div>
