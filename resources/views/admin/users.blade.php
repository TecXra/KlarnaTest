@extends('admin_layout')


@section('content')

@include('admin/partials/form/create_user_modal')
@include('admin/partials/form/update_user_modal') 

<div class="container" style="margin-top: 150px;margin-bottom: 100px">
	<div class="row">
        <div id="userUpdateMessage" class="col-sm-12">
        </div>
        <div class="col-xs-12">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {!! session()->get('message') !!}
                </div>
            @endif
        </div>
    </div>


    


	<div class="row">
		<div class="col-sm-12">
			<h1 class="pull-left">Hantera användare</h1>
			<button class="btn btn-default pull-right" data-toggle="modal" data-target="#createUserModal">Skapa ny</button>
		</div>
	</div>

	<br>

	<div class="row">
        <div class="col-sm-2 pull-left">
            <select class="form-control pull-left" id="filterUserType">
            	<option value="">Alla typer av användare</option>
	    		@foreach( $userTypes as $userType)
        			@if($savedType == $userType->id)
                        <option selected value="{{ $userType->id }}">{{ $userType->label }}</option>
                    @else
                        <option value="{{ $userType->id }}">{{ $userType->label }}</option>
                    @endif
	        	@endforeach
	    	</select>
        </div>

        <div class="col-sm-2 pull-right">
            <input id="filterSearch" type="text" class="form-control" value="{{$savedSearch}}" placeholder="Sök">
        </div>
    </div>


	<div class="row">
		<div id="dataTable" class="col-sm-12">
			@include('admin/partials/table/user_table')
	    </div>
	</div>
</div>
@endsection


@section('footer')


<script type="text/javascript">
	// $(document).ready(function() {
	//     $('#example').DataTable({
	//     	"language": {
 //                "url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Swedish.json"
 //            }
	//     });
	// } );

    function redirect(page) {
        var redirect = "";
        if(typeof page == "undefined" || page == null) {
            redirect = '/admin/anvandare';
        } else {
            redirect = '/admin/anvandare?page=' + page;
        }

        if (history.pushState) {
          window.history.pushState("", "", redirect);
        } else {
          document.location.href = redirect;
        }
        return;
    }
	
	$(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
		$('#message').empty();

        var userType = $('#filterUserType').val();
        var search = $('#filterSearch').val();
        var url = $(this).attr('href');
        var page = $(this).text();
        redirect(page);

        $.ajax({
            type: 'GET',
            url: url, 
            data : {
                userType : userType,
                search : search,
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                $('#dataTable').empty();
				$('#dataTable').append(data.table);
            }
        });
    });

    $(document).on('ifChecked', '#company', function() {
        $('#InputCompanyName').parent().removeClass('hidden');
        $('#InputCompanyName').attr('required', true);
        $('#InputCompanyName').attr('disabled', false);
    });

    $(document).on('ifChecked', '#private', function() {
        $('#InputCompanyName').parent().addClass('hidden');
        $('#InputCompanyName').attr('required', false);
        $('#InputCompanyName').attr('disabled', true);
    });

    $(document).on('ifChecked', '#EditIsCompany', function() {
        $('#EditInputCompanyName').parent().removeClass('hidden');
        $('#EditInputCompanyName').attr('required', true);
        $('#EditInputCompanyName').attr('disabled', false);
    });

    $(document).on('ifChecked', '#EditIsPrivate', function() {
        $('#EditInputCompanyName').parent().addClass('hidden');
        $('#EditInputCompanyName').attr('required', false);
        $('#EditInputCompanyName').attr('disabled', true);
    });
	
	$(document).on('ifChecked', '#isShippingAddress', function() {
		$('.shippingAdressForm').removeClass('hidden');
	});

	$(document).on('ifUnchecked', '#isShippingAddress', function() {
		$('.shippingAdressForm').addClass('hidden');
	});

	$(document).on('click', '#activate, #unActivate', function() {
		$('#message').empty();
		id = $(this).data('id');
		action = $(this).data('action');
		page = $('.pagination .active span ').text();
		console.log(page);
		// console.log($(this).data('id'));

		$.ajax({
			type: 'GET',
			url: 'anvandare',
			data: {
				id : id,
				action : action,
				page: page,
			},
			success: function(data) {
				console.log(data);
				$('#dataTable').empty();
				$('#dataTable').append(data.table);
			}
		});
	});

	$(document).on('submit', '#createUserForm', function(e) {
		e.preventDefault();
		$('#message').empty();

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
		});

		// console.log($("#DDTireType").val());
		// throw new Error('die');

		$.ajax({
		    type: 'POST',
		    url: 'storeUser', 
		    data: $('#createUserForm').serialize(),
		    dataType: 'json',
		    xhrFields: {
		        withCredentials: true
		    },
		    success: function(data) {

		    	if(data.status) {
		    		 window.location.href = '{{ url('admin/anvandare/') }}';
		    	} else {
			    	$('#message').append('<div class="alert alert-danger">' + data.error + '</div>');
		    	}



		    	// $('#createUserModal').modal('toggle');
		    	// $('#message').append('<div class="alert alert-success">' + data.message + '</div>');
		    	// $('#userCount').empty();
		    	// $('#userCount').append(data.userCount + " produkter");

		    }
		});
	});

	$(document).on('click', '#edit', function(e) {
        e.preventDefault();
        $('#message').empty();

        var id = $(this).data('id');

        // console.log(id);
        // throw new Error('die');

        $.ajax({
            type: 'GET',
            url: 'showUpdateUserModal', 
            data : {
                id : id,
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                console.log(data.user);
                // console.log(data.product.id);
                // $('.container').append(data.updateTireModal);
                
                $('#updateUserModal').modal('show');
                $('#userId').val(data.user.id);
                $('#EditInputFirstName').val(data.user.first_name);
                $("#EditInputLastName").val(data.user.last_name);
                $("#EditInputCompanyName").val(data.user.company_name);
                $("#EditInputEmail").val(data.user.email);
                $("#EditInputSSN").val(data.user.org_number);
                $("#EditInputPhone").val(data.user.billing_phone);
                
                $("#EditInputBillingStreet").val(data.user.billing_street_address);
                $("#EditInputBillingPostalCode").val(data.user.billing_postal_code);
                $("#EditInputBillingCity").val(data.user.billing_city);
                $("#EditInputBillingCountry").val(data.user.billing_country);

                $("#EditInputShippingStreet").val(data.user.shipping_street_address);
                $("#EditInputShippingPostalCode").val(data.user.shipping_postal_code);
                $("#EditInputShippingCity").val(data.user.shipping_city);
                $("#EditInputShippingCountry").val(data.user.shipping_country);

                if(data.user.is_company) {
                	console.log('1');
                    $("#EditIsCompany").iCheck('check');
                } else {
                    $("#EditIsPrivate").iCheck('check');
                }

                $.each(data.userTypes, function(i, val) {
                	console.log(i, val.id, data.user.user_type_id)
                    if(data.user.user_type_id ==  val.id) {
                    	$("#EditDDUserType").val(val.id);
                    }
                });
                

                // for(var i = 1; i <= 3; i++) {
                //     if(data.product.product_type_id == i) {
                //         $("#EditDDTireType option:eq(" + (i-1) + ")").attr("selected", "selected");
                //     }
                // }


            }
        });
    });

    $(document).on('submit', '#updateUserForm', function(e) {
        e.preventDefault();
        $('#userUpdateMessage').empty();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
		});

        // console.log($('#updateTireForm').serialize());
        // throw new Error('die');

        $.ajax({
            type: 'POST',
            url: 'updateUser', 
            data: $('#updateUserForm').serialize(),

            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
            	$('#updateUserModal').modal('toggle');
            	$('#userUpdateMessage').append('<div class="alert alert-success">' + data.message + '</div>');
            	$('#dataTable').empty();
				$('#dataTable').append(data.table);
            }
        });
    });

    $(document).on('change', '#userType', function() {
		// $('#message').empty();
		$('#userUpdateMessage').empty();

		var id = $(this).data('id');
		var userType = $(this).val();
		var page = $('.pagination .active span ').text();
		// console.log(page);
		// console.log($(this).data('id'));
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

		$.ajax({
			type: 'PATCH',
			url: 'updateUserType',
			data: {
				id : id,
				userType : userType,
				page: page,
			},
			success: function(data) {
				console.log(data);
				$('#userUpdateMessage').append('<div class="alert alert-success">' + data.message + '</div>');
				$('#dataTable').empty();
				$('#dataTable').append(data.table);
			}
		});
	});

    $(document).on('change', '#filterUserType', function() {
        var userType = $('#filterUserType').val();
        var search = $('#filterSearch').val();
        // var page = $('.pagination .active span ').text();
        redirect();

        $.ajax({
            type: "GET",
            url: 'filterUsers',
            data: {
                userType : userType,
                search : search,
                // page : page
            },
            success: function(data) {
                console.log(data);
                $('#dataTable').empty();
                $('#dataTable').append(data.table);
            }
        });
    });

    $(document).on('keyup', '#filterSearch', function() {
        var userType = $('#filterUserType').val();
        var search = $('#filterSearch').val();
        // var page = $('.pagination .active span ').text();
        redirect();

        $.ajax({
            type: "GET",
            url: 'filterUsers',
            data: {
                userType : userType,
                search : search,
                // page : page
            },
            success: function(data) {
                console.log(data);
                $('#dataTable').empty();
                $('#dataTable').append(data.table);
            }
        });
    });

</script>

@endsection