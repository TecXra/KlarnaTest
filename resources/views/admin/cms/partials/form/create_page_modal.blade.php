<div class="modal cmsForm fade" id="createPageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Ny sida</h2>
            </div>
            <div class="modal-body">
                    
	            <div class="row userInfo">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

	                <form {{-- action="storeUser" method="POST" --}} id="createPageForm" style="padding:15px">
	                	{{ csrf_field() }}
	                	<div class="row">
		                    <div class="col-sm-12">
						        <div class="message" class="col-sm-12">
						        </div>
						    </div>
						</div>

                    	<div class="row">

	                        <div class="col-sm-6 form-group required">
	                            <label>Sidnamn<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="InputPageLabel" name="InputPageLabel">
	                        </div>

	                        <div class="col-sm-6 form-group required">
	                            <label>url<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="InputPageName" name="InputPageName">
	                        </div>

	                       {{--  <div class="col-sm-4">
                   				<label>Prioritet 1-5</label>
                   				<select class="form-control" id="DDPriority" name="DDPriority">
                   					@for($i=0; $i <= 5; $i++)
										<option value="{{$i}}">{{$i}}</option>
                   					@endfor
                   				</select>
                   			</div> --}}

	                    </div>

	                    <div class="row">
							<div class="col-sm-12 form-group ">
	                            <label>Meta titel</label>
	                            <input type="text" class="form-control" id="InputMetaTitle" name="InputMetaTitle">
	                        </div>
	                    </div>

	                    <div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Meta beskrivning</label><br>
                                <input type="text" class="form-control" id="InputMetaDescription" name="InputMetaDescription">
                                
                            </div>
                        </div>

                        <div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Meta keywords</label><br>
                                <input type="text" class="form-control" id="InputMetaKeywords" name="InputMetaKeywords">
                                
                            </div>
                        </div>


                        <div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Innehåll</label><br>
                                <textarea class="form-control" id="content" name="content" rows=10></textarea>
                                
                            </div>
                        </div>
	                        
                        <hr>
						
						<div class="row">
		                    <div class="col-sm-12">
		                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Skapa sida</button>
		                    </div>
		                </div>

		                <br>

		                <div class="row">
		                    <div class="col-sm-12">
						        <div class="message" class="col-sm-12">
						        </div>
						    </div>
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