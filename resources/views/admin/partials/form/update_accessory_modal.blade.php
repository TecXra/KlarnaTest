<div class="modal productForm fade" id="updateAccessoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Uppdatera tillbehör</h2>
            </div>
            <div class="modal-body">
                
                    
	            <div class="row userInfo">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

					{{-- <div class="row">
						<div style="padding:30px;">
			                <form action="UploadImages" class=" dropzone" id="my-awesome-dropzone" enctype="multipart/form-data"></form>
			            </div>
					</div> --}}

					{{-- <div class="row">
		                <div style="padding:30px;">
			                <div id="dropzone-previews" class="pull-left"></div>
			            </div>
					</div> --}}

	                <form id="updateAccessoryForm" style="padding:15px">
	                	{{ csrf_field() }}
	                	{{-- <div class="dropzone-previews"></div> --}}

	                	<input type="hidden" id="EditProductID" name="EditProductID">

                    	<div class="row">
	                    	{{-- <div class="col-sm-4 form-group required">
	                    		<label>Fälg typ <sup>*</sup> </label>
	                            <select class="form-control" id="DDRimType" name="DDRimType">
	                                <option value="4">Aluminiumfälg</option>
	                                <option value="5">Stålfäg</option>
	                                <option value="6">Plåtfälg</option>
	                            </select>
	                        </div> --}}
	                        {{-- {{dd($productTypes)}} --}}

	                    	{{-- <div class="col-sm-4 form-group required">
	                    		<label>Type <sup>*</sup> </label>
	                            <select class="form-control" id="EditDDType" name="EditDDType">
	                            	@foreach($productTypes as $productType)
	                            		@if($productType->id == 7)
	                            			@continue
	                            		@endif

	                            		<option value="{{$productType->id}}">{{$productType->label}}</option>
	                            	@endforeach
	                            </select>
	                        </div> --}}

	                        <div class="col-sm-4 form-group ">
	                            <label> Produkt typ </label>
	                            <input type="text" class="form-control" id="EditDDType" name="EditDDType" disabled>
	                        </div>

	                        <div class="col-sm-4 form-group required">
	                            <label>Produkt namn <sup>*</sup></label>
	                            <input required type="text" class="form-control" id="EditInputName" name="EditInputName" placeholder="ex: JP302">
	                        </div>

	                        <div class="col-sm-4 form-group required">
	                            <label>Artikelnummer <sup>*</sup> </label>
	                            <input required type="text" class="form-control" id="EditInputArticleId" name="EditInputArticleId" placeholder="ex: JR10157143074BF">
	                        </div>
	                    </div>

	                    <hr>

                   		<div class="row">
	                   		<div class="col-sm-4 form-group required">
	                            <label>Dimension<sup>*</sup></label>
	                            <input required type="text" class="form-control"  id="EditInputDimension" name="EditInputDimension" placeholder="ex: 7">
	                        </div>
	                        <div class="col-sm-2 form-group hidden">
	                            <label>Dimension2 <sup>*</sup> </label>
	                            <input type="text" class="form-control" id="EditInputDimension2" name="EditInputDimension2" placeholder="ex: 19" disabled>
	                        </div>
	            
	                   		<div class="col-sm-2 form-group required">
	                            <label>Webbpris<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="EditInputWebPrice" name="EditInputWebPrice" placeholder="ex: 675">
	                        </div>
	                        <div class=" col-sm-2 form-group">
	                            <label>Ord.pris</label>
	                            <input type="text" class="form-control" id="EditInputOriginalPrice" name="EditInputOriginalPrice" placeholder="ex: 1355">
	                        </div>
	                        <div class="col-sm-2 form-group required">
	                            <label>lagerpris <sup>*</sup> </label>
	                            <input required type="text" class="form-control" id="EditInputStoragePrice" name="EditInputStoragePrice" placeholder="ex: 675">
	                        </div>

	                        <div class=" col-sm-2 form-group required">
	                            <label>Kvantitet<sup>*</sup> </label>
	                            <input required type="text" class="form-control" id="EditInputQuantity" name="EditInputQuantity" placeholder="ex: 39">
	                        </div>
	                        
                   		</div>

                   		<div class="row">
	                   		<div class="col-sm-2 form-group">
	                            <label>Nivå 1</label>
	                            <input type="text" class="form-control" id="EditInputDiscount1" name="EditInputDiscount1" placeholder="ex: 1" value="1">
	                        </div>
	                        <div class=" col-sm-2 form-group">
	                            <label>Nivå 2</label>
	                            <input type="text" class="form-control" id="EditInputDiscount2" name="EditInputDiscount2" placeholder="ex: 0.9" value="1">
	                        </div>
	                        <div class="col-sm-2 form-group">
	                            <label>Nivå 3 </label>
	                            <input type="text" class="form-control" id="EditInputDiscount3" name="EditInputDiscount3" placeholder="ex: 0.8" value="1">
	                        </div>
	                        <div class="col-sm-2 form-group">
	                            <label>Nivå 4 </label>
	                            <input type="text" class="form-control" id="EditInputDiscount4" name="EditInputDiscount4" placeholder="ex: 0.75" value="1">
	                        </div>
	                        {{-- <div class="col-sm-2 form-group">
	                            <label>Grossist </label>
	                            <input type="text" class="form-control" id="EditInputDiscount4" name="EditInputDiscount4" placeholder="ex: 0.9">
	                        </div> --}}
	                       
                   		</div>

    {{--                <div class="row">
                   			<div class="col-sm-2">
                   				<label>Prioritet 1-5</label>
                   				<select class="form-control" id="DDPriority" name="DDPriority">
                   					@for($i=0; $i <= 5; $i++)
										<option value="{{$i}}">{{$i}}</option>
                   					@endfor
                   				</select>
                   			</div>
                   		</div> --}}
                   		
                   		<hr>

                   		<div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Beskrivning</label><br>
                                <textarea class="form-control" id="EditDescription" name="EditDescription" rows=7></textarea>
                                
                            </div>
                        </div>

                       {{--  <div class="row">
							<div class="col-xs-12">
				                <div class="dropzone" id="create-accessory-form-dropzone" enctype="multipart/form-data"></div>
				            </div>
						</div> --}}

                        {{-- <div class="row">
                        	<div class="col-xs-12">
		                   		<div class="dropzone"></div>
							</div>
                        </div> --}}

                        <hr>
	                    

	                    <div class="col-sm-12">
	                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Uppdatera tillbehör</button>
	                    </div>
	                </form>

	               {{--  <div class="col-lg-12 clearfix">
	                    <ul class="pager">
	                        <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Go to Shop </a>
	                        </li>
	                        <li class="next pull-left"><a href="account.html"> &larr; Back to My Account</a></li>
	                    </ul>
	                </div>  --}}
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