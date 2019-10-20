<div class="modal cmsForm fade" id="updatePostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Nytt inlägg</h2>
            </div>
            <div class="modal-body">
                
                    
	            <div class="row userInfo">
	                <div class="col-sm-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    <p class="required"><sup>*</sup> Obligatoriska fält</p>
	                </div>

	                <form {{-- action="storeUser" method="POST" --}} id="updatePostForm" style="padding:15px">
	                	{{ csrf_field() }}
	                	<div class="row">
		                    <div class="col-sm-12">
						        <div class="message" class="col-sm-12">
						        </div>
						    </div>
						</div>

					    <input type="hidden" id="postId" name="postId" >

                    	<div class="row">

	                        <div class="col-sm-6 form-group required">
	                            <label>Sidnamn<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="EditInputPostLabel" name="EditInputPostLabel">
	                        </div>

	                        <div class="col-sm-6 form-group required">
	                            <label>url<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="EditInputPostName" name="EditInputPostName">
	                        </div>

	                    </div>

	                    <div class="row">
							<div class="col-sm-12 form-group required ">
	                            <label>Skribent<sup>*</sup></label>
	                            <input required type="text" class="form-control" id="EditInputAuthor" name="EditInputAuthor">
	                        </div>
	                    </div>

	                    <div class="row">
							<div class="col-sm-12 form-group ">
	                            <label>Meta titel</label>
	                            <input type="text" class="form-control" id="EditInputMetaTitle" name="EditInputMetaTitle">
	                        </div>
	                    </div>

	                     <div class="row">
							<div class="col-sm-12 form-group ">
	                            <label>Meta beskrivning</label>
	                            <input type="text" class="form-control" id="EditInputMetaDescription" name="EditInputMetaDescription">
	                        </div>
	                    </div>

	                    <div class="row">
							<div class="col-sm-12 form-group ">
	                            <label>Meta keywords</label>
	                            <input type="text" class="form-control" id="EditInputMetaKeywords" name="EditInputMetaKeywords">
	                        </div>
	                    </div>

	                    <div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Innehåll</label><br>
                                <textarea class="form-control" id="EditContent" name="EditContent" rows=10></textarea>
                                
                            </div>
                        </div>

                        <hr>
	                        
						<div class="row">
		                    <div class="col-sm-12">
		                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Uppdate inlägg</button>
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