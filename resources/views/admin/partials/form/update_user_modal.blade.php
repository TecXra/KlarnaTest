<div class="modal productForm fade" id="updateUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Uppdatera användare</h2>
            </div>
            <div class="modal-body">
                
                    
	            <div class="row userInfo">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

	                <form id="updateUserForm" style="padding:15px">
	                	{{ csrf_field() }}

	                	<input type="hidden" id="userId" name="userId">

                    	<div class="row">

	                    	<div class="col-sm-3 form-group required">
	                    		<label>Användartyp <sup>*</sup> </label>
	                            <select required class="form-control" id="EditDDUserType" name="EditDDUserType">
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
	                            <input required type="text" class="form-control" id="EditInputFirstName" name="EditInputFirstName">
	                        </div>

	                        <div class="col-sm-3 form-group required">
	                            <label>Efternamn<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="EditInputLastName" name="EditInputLastName">
	                        </div>

	                        <div class="col-sm-3 form-group required hidden">
	                            <label>Företagsnamn<sup>*</sup></label>
	                            <input type="text" class="form-control" id="EditInputCompanyName" name="EditInputCompanyName">
	                        </div>

	                    </div>

	                    <div class="row">
	                        <div class="col-sm-3 form-group required">
	                            <label>E-post <sup>*</sup> </label>
	                            <input required type="email" class="form-control" id="EditInputEmail" name="EditInputEmail">
	                        </div>

	                        <div class="col-sm-3 form-group">
	                            <label>Personnummer</label>
	                            <input type="text" class="form-control" id="EditInputSSN" name="EditInputSSN" placeholder="format: yymmddxxxx">
	                        </div>

	                        <div class="col-sm-3 form-group">
	                            <label>Telefonnummer</label>
	                            <input type="text" class="form-control" id="EditInputPhone" name="EditInputPhone">
	                        </div>

	                        <br>

							<div class="col-md-3">
                                <label class="checkbox-inline checked" for="checkboxes-0">
                                    <input name="EditIsCompany" id="EditIsPrivate" value="0" type="radio">
                                    Privat </label>
                                <label class="checkbox-inline" for="checkboxes-1">
                                    <input name="EditIsCompany" id="EditIsCompany" value="1" type="radio">
                                    Företag </label>
                            </div>
	                    </div>

	                    <h4 class="block-title-2">Faktura adress</h4>
                		
                		<div class="row">
	                   		<div class="col-sm-3 form-group">
	                            <label>Gata</label>
	                            <input type="text" class="form-control" id="EditInputBillingStreet" name="EditInputBillingStreet">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Postnummer</label>
	                            <input type="text" class="form-control" id="EditInputBillingPostalCode" name="EditInputBillingPostalCode">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Stad</label>
	                            <input type="text" class="form-control" id="EditInputBillingCity" name="EditInputBillingCity">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Land</label>
	                            <input type="text" class="form-control" id="EditInputBillingCountry" name="EditInputBillingCountry">
	                        </div>
                   		</div>

                   		{{-- <div class="row">

							<div class="col-md-4">
                                <label class="checkbox-inline" for="checkboxes-0">
                                    <input id="isShippingAddress" id="isShippingAddress" value="1" type="checkbox">
                                    Alternativ adress för leverans </label>
                            </div>
                   		</div> --}}

                   		<br>

                   		<h4 class="block-title-2 shippingAdressForm">Leverans adress</h4>

                   		<div class="row shippingAdressForm">
	                   		<div class="col-sm-3 form-group">
	                            <label>Gata</label>
	                            <input type="text" class="form-control" id="EditInputShippingStreet" name="EditInputShippingStreet">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Postnummer</label>
	                            <input type="text" class="form-control" id="EditInputShippingPostalCode" name="EditInputShippingPostalCode">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Stad</label>
	                            <input type="text" class="form-control" id="EditInputShippingCity" name="EditInputShippingCity">
	                        </div>
	                        <div class="col-sm-3 form-group">
	                            <label>Land</label>
	                            <input type="text" class="form-control" id="EditInputShippingCountry" name="EditInputShippingCountry">
	                        </div>
                   		</div>


                        <hr>
	                        

	                    <div class="col-sm-12">
	                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Uppdatera användare</button>
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