<div class="modal productForm fade" id="commentOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h2 class="text-center"> Kommentera</h2>
            </div>
            <div class="modal-body">
                
                    
	            <div class="row userInfo">
	                <div class="col-lg-12">
	                    {{-- <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2> --}}
	                    {{-- <p class="required"><sup>*</sup> Obligatoriska fält</p> --}}
	                </div>
	                <form id="commentOrderForm" style="padding:15px">
	                	{{ csrf_field() }}

	                	<input type="hidden" name="orderId" id="orderId">

                   		<div class="row">
	                   		<div class="col-sm-12 form-group">
                                <label for="textarea">Kommentar </label><br>
                                <textarea class="form-control" id="comment" name="comment" rows=10></textarea>
                            </div>
                        </div>
	                    
	                    <hr>

	                    <div class="col-sm-12">
	                        <button id="commentOrder" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Spara </button>
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
                


            </div>
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