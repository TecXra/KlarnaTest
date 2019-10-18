@extends('admin_layout')


@section('content')

{{-- <style>
    .mce-container,
    .mce-menu,
    #mceu_*,
    .mce_menu {
        top:-500px !important;
    }
</style> --}}
@include('admin/cms/partials/form/create_page_modal')
@include('admin/cms/partials/form/update_page_modal') 

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
        			<h1 class="pull-left">Hantera sidor</h1>
        			<button class="btn btn-default pull-right" data-toggle="modal" data-target="#createPageModal">Skapa ny</button>
        		</div>
        	</div>

        	<br>

        	{{-- <div class="row">

                <div class="col-sm-2 pull-right">
                    <input id="filterSearch" type="text" class="form-control" placeholder="Sök">
                </div>
            </div> --}}

        	<div class="row">
                
        		<div id="dataTable" class="col-sm-12">
        			@include('admin/cms/partials/table/pages_table')
        	    </div>
        	</div>

        </div>
    </div>

</div>
@endsection


@section('footer')

<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    $('#createPageModal').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });

$(function() {
        var editor_config = {
          path_absolute : "{{ URL::to('/admin') }}/",
          selector : "textarea",
          height : 350,
          plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
          ],
          toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor",
          relative_urls: false,
          remove_script_host : false,
          file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.grtElementByTagName('body')[0].clientHeight;
            var cmsURL = editor_config.path_absolute+'laravel-filemanager?field_name'+field_name;
            if (type = 'image') {
              cmsURL = cmsURL+'&type=Images';
            } else {
              cmsUrl = cmsURL+'&type=Files';
            }

            tinyMCE.activeEditor.windowManager.open({
              file : cmsURL,
              title : 'Filemanager',
              width : x * 0.8,
              height : y * 0.8,
              resizeble : 'yes',
              close_previous : 'no'
            });
          }
        };
        tinymce.init(editor_config);
    });
    // $(document).ready(function() {
    //     $('#example').DataTable({
    //      "language": {
 //                "url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Swedish.json"
 //            }
    //     });
    // } );
    
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
        $('.message').empty();

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
                    $('.message').append('<div class="alert alert-danger">' + data.message + '</div>');
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
                $('#updatePageModal').on('shown.bs.modal', function() {
                    $(document).off('focusin.modal');
                });

                $('#pageId').val(data.page.id);
                $('#EditInputPageLabel').val(data.page.label);
                $('#EditInputPageName').val(data.page.name);
                $('#EditInputMetaTitle').val(data.page.meta_title);
                $('#EditInputMetaDescription').val(data.page.meta_description);
                $('#EditInputMetaKeywords').val(data.page.meta_keywords);
                // $("#EditContent").val(data.post.content);
                tinyMCE.activeEditor.setContent(data.page.content);

                // $.each(data.pages, function(i, val) {
                //     // console.log(i, val.id, data.page.page_type_id)
                //     if(data.post.page_id ==  val.id) {
                //         $("#EditDDPageID").val(val.id);
                //     }
                // });
                

                // for(var i = 1; i <= 3; i++) {
                //     if(data.product.product_type_id == i) {
                //         $("#EditDDTireType option:eq(" + (i-1) + ")").attr("selected", "selected");
                //     }
                // }


            }
        });

        // $.ajax({
            // type: 'GET',
            // url: 'showUpdatePageModal', 
            // data : {
             //   id : id,
            // },
            // dataType: 'json',
            // xhrFields: {
               // withCredentials: true
            // },
            // success: function(data) {
                // console.log(data.page);
                // console.log(data.product.id);
                // $('.container').append(data.updateTireModal);
                
                // $('#updatePostModal').modal('show');
                // $('#pageId').val(data.page.id);
                // $('#EditInputPageName').val(data.page.label);
                // $("#EditInputMetaTitle").val(data.page.meta_title);
                // $("#EditMetaDescription").val(data.page.meta_description);

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


          //  }
        //});
    });

    $(document).on('submit', '#updatePageForm', function(e) {
        e.preventDefault();
        $('#userUpdateMessage').empty();
        $('.message').empty();

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
                if(data.status) {
                    $('#updatePageModal').modal('toggle');
                    $('#userUpdateMessage').append('<div class="alert alert-success">' + data.message + '</div>');
                    $('#dataTable').empty();
                    $('#dataTable').append(data.table);
                } else {
                    $('.message').append('<div class="alert alert-danger">' + data.message + '</div>');
                }
            }
        });
    });

    $(document).on('keyup', '#InputPageLabel', function() {
        var str = $(this).val().replace(/\ö/g,  'o').replace(/\ä/g,  'a').replace(/\å/g,  'a').replace(/\ /g,  '').toLowerCase();
        $('#InputPageName').val(str);
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