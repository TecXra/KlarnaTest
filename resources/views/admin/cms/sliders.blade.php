@extends('admin_layout')

@section('header')
    {{-- <script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script> --}}
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
@endsection

@section('content')

@include('admin/cms/partials/form/create_slider_modal')
@include('admin/cms/partials/form/update_slider_modal') 

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
        			<h1 class="pull-left">Hantera sliders</h1>
        			<button class="btn btn-default pull-right" data-toggle="modal" data-target="#createSliderModal">Skapa ny</button>
        		</div>
        	</div>

        	<br>

        	<div class="row">

                <div class="col-sm-2 pull-right">
                    <label>Välj sida</label>
                    <select class="form-control" id="pageList" name="pageList">
                        @foreach($ddPages as $page)
                            <option {{ Request::input('pageId') ==  $page->id ? 'selected' : '' }} value="{{$page->id}}">{{$page->label}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        	<div class="row">
                
        		<div id="dataTable" class="col-sm-12">
        			@include('admin/cms/partials/table/sliders_table')
        	    </div>
        	</div>

        </div>
    </div>

</div>
@endsection

@section('footer')

<script src="{{ asset('assets/js/dropzone.js') }}"></script>

<script type="text/javascript">

    $( function() {
        $( "#sortable" ).sortable({
            stop: function(event, ui) {
                var idsInOrder = $("#sortable").sortable("toArray");
               //-----------------^^^^
               console.log(idsInOrder);
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
               
                $.ajax({
                    type: 'post',
                    url: 'sortSlider',
                    data: {
                        sliderList : $("#sortable").sortable("toArray"),
                        pageId : $("#pageList").val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log('sort slider');
                        // console.log(data.firstMenu, data.secondMenu, data.menuId);
                        // $('#dataTable').empty();
                        // $('#dataTable').append(data.table);
                    }
                })
               

            }
        }).disableSelection();
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        Dropzone.options.createSliderFormDropzone = { // The camelized version of the ID of the form element

            // The configuration we've talked about above
            url: 'storeSlider',
            autoProcessQueue: false,
            autoDiscover: false,
            addRemoveLinks: true,
            acceptedFiles: '.jpg, .jpeg, .png, .gif',
            paramName: 'file', // is default
            uploadMultiple: true,
            parallelUploads: 1,
            maxFiles: 1,
            dictRemoveFile: 'Ta bort bild',
            dictDefaultMessage: 'Droppa in bilder här för uppladdning',
            sending: function(file, xhr, formData) {
                // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
                
            },


            // The setting up of the dropzone
            init: function() {
                var myDropzone = this;

                // First change the button to actually tell Dropzone to process the queue.
                // this.element.querySelector("button").addEventListener("submit", function(e) {
                $('#createSliderForm').on("submit", function(e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();
                    e.stopPropagation();
                    if (myDropzone.getQueuedFiles().length > 0) {                        
                       myDropzone.processQueue();  
                    } else {                       
                       myDropzone.uploadFiles([]);
                    }
                });

                // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                // of the sending event because uploadMultiple is set to true.
                this.on("sendingmultiple", function(file, xhr, formData) {

                    formData.append("_token", $("[name=_token]").val()); // Laravel expect the token post value to be named _token by default
                    var ser = $('#createSliderForm').serialize()

                    $('#pageId').val($('#pageList').val());
                    // console.log(page);
                    formData.append("formData", $('#createSliderForm').serialize());
                    // formData.append("pageId", );
                    // Gets triggered when the form is actually being sent.
                    // Hide the success button or the complete form.
                });
                this.on("successmultiple", function(files, data) {
                    // Gets triggered when the files have successfully been sent.
                    // Redirect user or notify of success.
                    window.location.href = '{{ url('admin/cms/slider?pageId='.Request::input('pageId') ) }}';

                    // $('#createRimModal').modal('toggle');
                    // $('#message').append('<div class="alert alert-success">' + data.message + '</div>');
                    // $('#productCount').empty();
                    // $('#productCount').append(data.productCount + " produkter");
                });
                this.on("errormultiple", function(files, response) {
                    // Gets triggered when there was an error sending the files.
                    // Maybe show form again, and notify user of error
                });
            }

        } 

        Dropzone.options.updateSliderFormDropzone = { // The camelized version of the ID of the form element

            // The configuration we've talked about above
            url: 'updateSlider',
            autoProcessQueue: false,
            autoDiscover: false,
            addRemoveLinks: true,
            acceptedFiles: '.jpg, .jpeg, .png, .gif',
            paramName: 'file', // is default
            uploadMultiple: true,
            // parallelUploads: 3,
            // maxFiles: 3,
            dictRemoveFile: 'Ta bort bild',
            dictDefaultMessage: 'Droppa in sliderbild här för uppladdning',
            sending: function(file, xhr, formData) {
                // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
            },
            // The setting up of the dropzone
            init: function() {
                var myDropzone = this;

                // First change the button to actually tell Dropzone to process the queue.
                // this.element.querySelector("button").addEventListener("submit", function(e) {
                $('#updateSliderForm').on("submit", function(e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();
                    e.stopPropagation();
                    if (myDropzone.getQueuedFiles().length > 0) {                        
                       myDropzone.processQueue();  
                    } else {                       
                       myDropzone.uploadFiles([]);
                    }
                });

                // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                // of the sending event because uploadMultiple is set to true.
                this.on("sendingmultiple", function(file, xhr, formData) {
                    formData.append("_token", $("[name=_token]").val()); // Laravel expect the token post value to be named _token by default
                    var ser = $('#updateSliderForm').serialize()
                    // console.log(ser);
                    formData.append("formData", $('#updateSliderForm').serialize());

                    // Gets triggered when the form is actually being sent.
                    // Hide the success button or the complete form.
                });
                this.on("successmultiple", function(files, data) {
                    // Gets triggered when the files have successfully been sent.
                    // Redirect user or notify of success.
                    // myDropzone.uploadFiles([]);
                    window.location.href = '{{ url('admin/cms/slider?pageId='.Request::input('pageId') ) }}';
                    // $('#updateSliderModal').modal('toggle');
                    // $('#dataTable').empty();
                    // $('#dataTable').append(data.table);
                    // $('#message').append('<div class="alert alert-success">' + data.message + '</div>');
                });
                this.on("errormultiple", function(files, response) {
                    // Gets triggered when there was an error sending the files.
                    // Maybe show form again, and notify user of error
                });
            }

        } 
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
        // page = $('.pagination .active span ').text();
        pageId = $('#pageList').val();
        // console.log(pageId);
        // console.log($(this).data('id'));

        $.ajax({
            type: 'GET',
            url: 'slider',
            data: {
                id : id,
                action : action,
                pageId: pageId,
            },
            success: function(data) {
                console.log(data);
                $('#dataTable').empty();
                $('#dataTable').append(data.table);
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
            url: 'showUpdateSliderModal', 
            data : {
                id : id,
            },
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                console.log(data.slider);
                // console.log(data.product.id);
                // $('.container').append(data.updateTireModal);
                if(data.slider.path) {
                    $('.slider_img').removeClass('hidden');
                    $('.edit_slider_image .dropzone').addClass('hidden')
                } else {
                    $('.slider_img').addClass('hidden');
                    $('.edit_slider_image .dropzone').removeClass('hidden')
                }
                
                
                $('.edit_slider_image .slider_img').empty();
                $('.dropzone').removeClass('dz-started');
                $('.dz-preview').empty();

                
                $('.edit_slider_image .slider_img').append(
                    // '<div class="col-sm-2 pull-left">' +
                        '<img style="width: 100%; height: auto;" src="../../'
                        + data.slider.path +
                        '"><br><br>' +
                        '<a href="#" data-id="' + data.slider.id + '" class="pull-right" style="border:none;background:none; color: #B54035; font-size: 1em">Radera bild <i class="glyphicon glyphicon-trash fa-2x"></i></a>'
                    // '</div>'
                );

                // for (var i in data.images) {
                //     $('.product_images .col-xs-12').append(
                //         '<div class="col-sm-2 pull-left">' +
                //             '<img height="70" src="../'
                //             +data.images[i].thumbnail_path+
                //             '"><br>' +
                //             '<a href="#" data-id="' + data.images[i].id + '">Radera bild</a>' +
                //         '</div>'
                //     );
                // }



                $('.edit_slider_image .slider_img a').on('click', function(e) {
                    e.preventDefault();

                    $this = $(this);
                    var id = $(this).data("id");

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'DELETE',
                        url: 'sliderImage/' + id, 
                        dataType: 'json',
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function(data) {
                            
                            $this.parents('.slider_img').addClass('hidden');
                            $this.parents('.edit_slider_image').find('.dropzone').removeClass('hidden')
                            console.log('success!!!!!!!!!');
                        }
                    });

                });

                $('#updateSliderModal').modal('show');
                $('#EditPageId').val(data.slider.page_id);
                $('#EditSliderId').val(data.slider.id);
                $('#EditInputSliderTitle').val(data.slider.title);
                // $("#EditContent").val(data.post.content);
                // 
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
    });

    // $(document).on('submit', '#updateSliderForm', function(e) {
    //     e.preventDefault();
    //     $('#userUpdateMessage').empty();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     // console.log($('#updateTireForm').serialize());
    //     // throw new Error('die');

    //     $.ajax({
    //         type: 'POST',
    //         url: 'updateSlider', 
    //         data: $('#updateSliderForm').serialize(),

    //         dataType: 'json',
    //         xhrFields: {
    //             withCredentials: true
    //         },
    //         success: function(data) {
    //             console.log('funkar eller??????')
    //             $('#updateSliderModal').modal('toggle');
    //             $('#userUpdateMessage').append('<div class="alert alert-success">' + data.message + '</div>');
    //             $('#dataTable').empty();
    //             $('#dataTable').append(data.table);
    //         }
    //     });
    // });

    $(document).on('change', '#pageList', function() {
        console.log('pageList');
        $.ajax({
            type: 'GET',
            url: 'slider',
            data: {
                pageId : $('#pageList').val()
            },
            dataType: 'json',
            success: function(data) {
                window.location.href = '{{ url('admin/cms/slider?pageId=') }}' + $('#pageList').val();
                // $('#dataTable').empty();
                // $('#dataTable').append(data.table);
            }
        })
    })

</script>

@endsection