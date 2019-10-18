<div class="modal productForm fade" id="itemNotificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Lägg beställning </h2>
            </div>
            <div class="modal-body">
                
	            <div class="row">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

	                {{-- <form id="itemNotificationForm" action="{{ url('admin/order/sendItemNotification') }}" method="POST" style="padding:15px"> --}}
	                <form id="itemNotificationForm" action="{{ url('admin/creatEonTyreOrder') }}" method="POST" style="padding:15px">
	                	{{ csrf_field() }}
	                	{{-- <div class="dropzone-previews"></div> --}}
	                	<h3 class="block-title-3" style="font-size: 1.2em">Leveransadress</h3>
						<div class="row">
							<div class="col-sm-3 form-group required">
	                            <label>Namn <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="fullName" value="{{ $order->shipping_full_name }}">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                            <label>Email <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="email" value="{{ $order->user->email }}">
	                        </div>
					
							<div class="col-sm-3 form-group required">
	                            <label>Telefon <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="phone" value="{{ $order->shipping_phone }}">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                        	<br>
	                            <div class=" form-group">
	                                <label class="checkbox-inline" for="checkboxes-0">
	                                    <input checked name="isCompany" id="private" value="2" type="radio">
	                                    Privat </label>
	                                <label class="checkbox-inline" for="checkboxes-1">
	                                    <input name="isCompany" id="company" value="1" type="radio" 
	                                    {{ $order->is_company ? "checked" : null }}>
	                                    Företag </label>
	                            </div>
	                        </div>
						</div>

						<div class="row">
							<div class="col-sm-3 form-group required">
	                            <label>Gata <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="streetAddress" value="{{ $order->shipping_street_address }}">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                            <label>Postnummer <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="postalCode" value="{{ $order->shipping_postal_code }}">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                            <label>Stad <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="city" value="{{ $order->shipping_city }}">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                            <label>Land <sup>*</sup> </label>
	                            <input required type="text" class="form-control" name="country" value="{{ $order->shipping_country }}">
	                        </div>
						</div>

						<div class="row">
							<div class="col-sm-6 form-group required">
	                        	<br>
	                            <div class=" form-group">
	                                <label class="checkbox-inline" for="checkboxes-0">
	                                    <input checked name="deliveryOption" value="0" type="radio">
	                                    Hämtas från butik </label>
	                                <label class="checkbox-inline" for="checkboxes-1">
	                                    <input name="deliveryOption" value="1" type="radio" {{ $order->delivery_method_id == 2 ? 'checked' : ''}}>
	                                    Skickas till kund </label>
	                            </div>
	                        </div>
						</div>

						<h3 class="block-title-3" style="font-size: 1.2em">Produkter</h3>
						@foreach($order->orderDetails as $item)
							@if($item->product->main_supplier_id == 2 ||  $item->product->main_supplier_id == 3)

		                    	<div class="row" >
		                    		<input type="hidden" name="items[{{ $item->id}}][supplier]" value="{{$item->product->mainSupplier->company_name}}">

		                    		<input type="hidden" name="items[{{ $item->id}}][article_number]" value="{{$item->product->main_supplier_product_id}}">

		                    		<input type="hidden" name="items[{{ $item->id}}][product_code]" value="{{$item->product->product_code}}">
									<div class="col-sm-6">
		                    			<h5 style="padding-top: 20px;">{{$item->product->product_code}}</h5>
		                    		</div>
			                        <div class="col-sm-2 form-group required">
			                            <label>Kvantitet <sup>*</sup> </label>
			                            <input required type="text" class="form-control" name="items[{{ $item->id}}][quantity]" value="{{ $item->quantity }}">
			                        </div>

			                        <div class="col-sm-2 form-group required">
			                            <label>Nettopris (kr)</label>
			                            <input required type="text" class="form-control" name="items[{{ $item->id}}][net_price]" value="{{ $item->net_price }} " disabled> 
			                        </div>
			                        <input type="hidden" name="items[{{ $item->id}}][net_price]" value="{{ $item->net_price }}">

			                        <div class="col-sm-2 form-group required">
			                            <label>Ordernummer </label>
			                            <input required type="text" class="form-control" name="items[{{ $item->id}}][order_id]" value="#{{$item->order_id}}" disabled>
			                        </div>
			                        <input type="hidden" name="order_id" value="{{$item->order_id}}">
			                        <input type="hidden" name="reference" value="{{$item->order->reference}}">

			                    </div>
		                   		
		                   		<hr>

		                   	@endif
	                   	@endforeach

                   		{{-- <div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Beskrivning</label><br>
                                <textarea class="form-control" id="description" name="description" rows=7></textarea>
                                
                            </div>
                        </div>  --}}

                        {{-- <div class="row">
	                   		<div class="col-sm-12 form-group">
                                <input type="checkbox" value="1" name="shipLater" id="shipLater">
                                <label for="shipLater">Invänta fler ordrar med däck.</label>  (Detta för att Gummi Grossen ger fri frakt på beställningar med 8 däck.)<br>
                            </div>
                        </div>        --}}                 
	                        
	                    <div class="col-sm-12">
	                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Beställ </button>
	                    </div>
	                </form>
	            </div>
	            <!--/row end-->

            </div><!--modal body-->
            <div class="modal-footer">
                {{-- <p class="text-center"> Första besöket? <a data-toggle="modal" data-dismiss="modal" href="#ModalSignup"> Registrera dig här. </a> <br>
                    <a href="#"> Glömt lösenord? </a></p> --}}
            </div>
        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal-dialog -->

</div>
<!-- /.Modal Login -->