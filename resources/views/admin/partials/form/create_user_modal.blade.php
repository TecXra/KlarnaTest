<div class="modal productForm fade" id="createUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Ny användare</h2>
            </div>
            <div class="modal-body">
                
                    
	            <div class="row userInfo">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

	                <form {{-- action="storeUser" method="POST" --}} id="createUserForm" style="padding:15px">
	                	{{ csrf_field() }}
	                	<div class="row">
					        <div id="message" class="col-sm-12">
					        </div>
					    </div>

                    	<div class="row">


	                    	<div class="col-sm-3 form-group required">
	                    		<label>Användartyp <sup>*</sup> </label>
	                            <select required class="form-control" id="DDUserType" name="DDUserType">
	                                <option></option>
	                                @foreach( $userTypes as $userType)
			                			<option value="{{ $userType->id }}">{{ $userType->label }}</option>
				                	@endforeach
		                            {{-- @foreach($brands as $brand)
		                                <option value="{{ $brand->product_brand }}">{{ $brand->product_brand }}</option>
		                            @endforeach --}}
	                            </select>
	                        </div>
	                        <div class="col-sm-3 form-group required">
	                            <label>Förnamn<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="InputFirstName" name="InputFirstName">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                            <label>Efternamn<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="InputLastName" name="InputLastName">
	                        </div>

	                        <div class="col-sm-3 form-group required hidden">
	                            <label>Företagsnamn<sup>*</sup></label>
	                            <input type="text" class="form-control" id="InputCompanyName" name="InputCompanyName">
	                        </div>

	                    </div>

	                    <div class="row">
	                        <div class="col-sm-3 form-group required">
	                            <label>E-post <sup>*</sup> </label>
	                            <input required type="email" class="form-control" id="InputEmail" name="InputEmail">
	                        </div>

	                        <div class="col-sm-3 form-group">
	                            <label>Personnummer</label>
	                            <input type="text" class="form-control" id="InputSSN" name="InputSSN" placeholder="format: yymmddxxxx">
	                        </div>

	                        <div class="col-sm-3 form-group">
	                            <label>Telefonnummer</label>
	                            <input type="text" class="form-control" id="InputPhone" name="InputPhone">
	                        </div>

	                        <br>

							<div class="col-md-3">
                                <label class="checkbox-inline" for="checkboxes-0">
                                    <input checked name="isCompany" id="private" value="0" type="radio">
                                    Privat </label>
                                <label class="checkbox-inline" for="checkboxes-1">
                                    <input name="isCompany" id="company" value="1" type="radio">
                                    Företag </label>
                            </div>
	                    </div>

	                    <h4 class="block-title-2">Faktura adress</h4>
                		
                		<div class="row">
	                   		<div class="col-sm-3 form-group">
	                            <label>Gata</label>
	                            <input type="text" class="form-control" id="InputBillingStreet" name="InputBillingStreet">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Postnummer</label>
	                            <input type="text" class="form-control" id="InputBillingPostalCode" name="InputBillingPostalCode">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Stad</label>
	                            <input type="text" class="form-control" id="InputBillingCity" name="InputBillingCity">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Land</label>
	                            <input type="text" class="form-control" id="InputBillingCountry" name="InputBillingCountry">
	                        </div>
                   		</div>

                   		<div class="row">

							<div class="col-md-4">
                                <label class="checkbox-inline" for="checkboxes-0">
                                    <input id="isShippingAddress" name="isShippingAddress" value="1" type="checkbox">
                                    Alternativ adress för leverans </label>
                            </div>
                   		</div>

                   		<br>

                   		<h4 class="block-title-2 shippingAdressForm hidden">Leverans adress</h4>

                   		<div class="row shippingAdressForm hidden">
	                   		<div class="col-sm-3 form-group">
	                            <label>Gata</label>
	                            <input type="text" class="form-control" id="InputShippingStreet" name="InputShippingStreet">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Postnummer</label>
	                            <input type="text" class="form-control" id="InputShippingPostalCode" name="InputShippingPostalCode">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Stad</label>
	                            <input type="text" class="form-control" id="InputShippingCity" name="InputShippingCity">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Land</label>
	                            <input type="text" class="form-control" id="InputShippingCountry" name="InputShippingCountry">
	                        </div>
                   		</div>


                        <hr>
	                        

	                    <div class="col-sm-12">
	                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Skapa användare</button>
	                    </div>
	                </form>

	            </div>
	            <!--/row end-->

            </div><!--modal body-->

        </div>
        <!-- /.modal-content -->

    </div>
    <!-- /.modal-dialog -->

</div>
<!-- /.Modal Login -->