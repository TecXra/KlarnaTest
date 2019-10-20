<div class="modal productForm fade" id="createSliderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Ny slider</h2>
            </div>
            <div class="modal-body">
                    
	            <div class="row userInfo">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

	                <form {{-- action="storeUser" method="POST" --}} id="createSliderForm" style="padding:15px">
	                	{{ csrf_field() }}
	                	<div class="row">
					        <div id="message" class="col-sm-12">
					        </div>
					    </div>

					    <input type="hidden" id="pageId" name="pageId">

                    	<div class="row">

	                        <div class="col-sm-12 form-group required">
	                            <label>Alt titel<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="InputSliderTitle" name="InputSliderTitle">
	                        </div>

	                    </div>

	                   	<div class="row slider_image"><div class="col-xs-12"></div></div>
                        <br>

                        <div class="row">
							<div class="col-xs-12">
				                <div {{-- action="storeRim"  --}} class="dropzone" id="create-slider-form-dropzone" enctype="multipart/form-data"></div>
				            </div>
						</div>

                        <hr>
	                        
	                    <div class="col-sm-12">
	                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Skapa slider</button>
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