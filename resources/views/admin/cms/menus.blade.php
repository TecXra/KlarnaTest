@extends('admin_layout')

@section('content')
<style>
    #sortable1, #sortable2 {
    border: 1px solid #eee;
    min-height: 20px;

  }
  #sortable1 li, #sortable2 li {
    /*margin: 0 5px 5px 5px;*/
  }
</style>
<div class="container" style="margin-top: 150px;margin-bottom: 100px">
    <div class="row">

        <div id="sideMenu" class="col-sm-2" style="margin-top: 0px;">
            @include('admin/cms/partials/cms_menu')
        </div>

        <div class="col-sm-10" style="border-left: 1px solid #ddd; padding-left:30px">
    
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
        			<h1 class="pull-left">Hantera menyer</h1>
        			{{-- <button class="btn btn-default pull-right" data-toggle="modal" data-target="#createPageModal">Skapa ny</button> --}}
                    <div class="col-sm-4 pull-right">
                        <label>Välj meny</label>
                        <select class="form-control" id="menuList" name="menuList">
                            @foreach($menus as $menu)
                                <option {{ Request::input('menuId') ==  $menu->id ? 'selected' : '' }} value="{{$menu->id}}">{{$menu->name}}</option>
                            @endforeach
                        </select>
                    </div>
        		</div>
        	</div>

        	<br>

        	<div class="row">

               {{--  <div class="col-sm-2 pull-right">
                    <input id="filterSearch" type="text" class="form-control" placeholder="Sök">
                </div> --}}
                
            </div>
            
        	<div class="row">
                
        		<div id="dataTable" class="col-sm-12">
        			@include('admin/cms/partials/table/menu_table')
        	    </div>
        	</div>

        </div>
    </div>

</div>
@endsection


@section('footer')


<script type="text/javascript">
    // $(document).ready(function() {
    //     $('#example').DataTable({
    //      "language": {
 //                "url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Swedish.json"
 //            }
    //     });
    // } );
    // 
    
    $( function() {
        $( "#sortable1, #sortable2" ).sortable({
            connectWith: ".connectedSortable",
            stop: function(event, ui) {
                var firstIdsInOrder = $("#sortable1").sortable("toArray");
                var secondIdsInOrder = $("#sortable2").sortable("toArray");
               //-----------------^^^^
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
               
                $.ajax({
                    type: 'post',
                    url: 'sortMenu',
                    data: {
                        firstMenu : $("#sortable1").sortable("toArray"),
                        secondMenu : $("#sortable2").sortable("toArray"),
                        menuId : $("#menuList").val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data.firstMenu, data.secondMenu, data.menuId);
                        // $('#dataTable').empty();
                        // $('#dataTable').append(data.table);
                    }
                })
               

            }
        }).disableSelection();
    });
    
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        $('#message').empty();

        var search = $('#filterSearch').val();
        var url = $(this).attr('href');

        $.ajax({
            type: 'GET',
            url: url, 
            data : {
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


    $(document).on('click', '#activate, #unActivate, #delete', function() {
        $('#message').empty();

        if($(this).attr('id') == 'delete' ) {
            if(!confirm('Är du säker på att du vill radera produkten?'))
                return;
        }

        id = $(this).data('id');
        action = $(this).data('action');
        page = $('.pagination .active span ').text();
        console.log(page);
        // console.log($(this).data('id'));

        $.ajax({
            type: 'GET',
            url: 'sidor',
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

    $(document).on('submit', '#createPageForm', function(e) {
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
            url: 'storePage', 
            data: $('#createPageForm').serialize(),
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {

                if(data.status) {
                     window.location.href = '{{ url('admin/cms/sidor/') }}';
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
            url: 'showUpdatePageModal', 
            data : {
                id : id,
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                console.log(data.page);
                // console.log(data.product.id);
                // $('.container').append(data.updateTireModal);
                
                $('#updatePageModal').modal('show');
                $('#pageId').val(data.page.id);
                $('#EditInputPageName').val(data.page.label);
                $("#EditInputMetaTitle").val(data.page.meta_title);
                $("#EditMetaDescription").val(data.page.meta_description);

                // $.each(data.pageTypes, function(i, val) {
                //     console.log(i, val.id, data.page.page_type_id)
                //     if(data.page.user_type_id ==  val.id) {
                //         $("#EditDDUserType").val(val.id);
                //     }
                // });
                

                // for(var i = 1; i <= 3; i++) {
                //     if(data.product.product_type_id == i) {
                //         $("#EditDDTireType option:eq(" + (i-1) + ")").attr("selected", "selected");
                //     }
                // }


            }
        });
    });

    $(document).on('submit', '#updatePageForm', function(e) {
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
            url: 'updatePage', 
            data: $('#updatePageForm').serialize(),

            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                $('#updatePageModal').modal('toggle');
                $('#userUpdateMessage').append('<div class="alert alert-success">' + data.message + '</div>');
                $('#dataTable').empty();
                $('#dataTable').append(data.table);
            }
        });
    });

    $(document).on('change', '#menuList', function() {
        $.ajax({
            type: 'GET',
            url: 'menyer',
            data: {
                menuId : $('#menuList').val()
            },
            dataType: 'json',
            success: function(data) {
                window.location.href = '{{ url('admin/cms/menyer?menuId=') }}' + $('#menuList').val();
                // $('#dataTable').empty();
                // $('#dataTable').append(data.table);
            }
        })
    })

    // $(document).on('change', '#priority', function() {
    //     // $('#message').empty();
    //     $('#userUpdateMessage').empty();

    //     var id = $(this).data('id');
    //     var priority = $(this).val();
    //     var page = $('.pagination .active span ').text();
    //     console.log(page);
    //     // console.log($(this).data('id'));
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     $.ajax({
    //         type: 'PATCH',
    //         url: 'updatePriority',
    //         data: {
    //             id : id,
    //             priority : priority,
    //             page: page,
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             $('#pageUpdateMessage').append('<div class="alert alert-success">' + data.message + '</div>');
    //             $('#dataTable').empty();
    //             $('#dataTable').append(data.table);
    //         }
    //     });
    // });


    // $(document).on('keyup', '#filterSearch', function() {
    //     var userType = $('#filterUserType').val();
    //     var search = $('#filterSearch').val();
    //     var page = $('.pagination .active span ').text();

    //     $.ajax({
    //         type: "GET",
    //         url: 'filterUsers',
    //         data: {
    //             userType : userType,
    //             search : search,
    //             page : page
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             $('#dataTable').empty();
    //             $('#dataTable').append(data.table);
    //         }
    //     });
    // });


</script>

@endsection