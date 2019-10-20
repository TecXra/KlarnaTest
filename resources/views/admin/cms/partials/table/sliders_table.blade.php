<span id="userCount" >Listar {{ $sliders->total() }} sliders</span>
<table id="example" class="table table-hover " cellspacing="0" width="100%" style="border-top: 1px solid #ddd">
     <thead>
        <tr>
            <th style="width: 5%;">Id</th>
            <th style="width: 70%;">Bild</th>
            <th style="width: 10%;">Titel</th>
            <th style="width: 13%;">Hantera</th>
        </tr>
    </thead>
{{--    <tfoot>
        <tr>
            <th>Id</th>
            <th>Namn</th>
            <th>Hantera</th>
        </tr>
    </tfoot> --}}
    <tbody id="sortable">
    {{-- {{dd($page->sliders())}} --}}
        @foreach($sliders as $slider)
            <tr id="{{$slider->id}}">
                <td>{{ $slider->id }}</td>
                <td>
                    @if($slider->path)
                        <img height=100 src="{{ url($slider->path) }}" alt="{{$slider->title}}">
                    @else
                       <img height=100 src="{{ asset('images/site/no_image_available.png') }}">
                    @endif
                </td>
                <td>{{ $slider->title }}</td>
                <td style="width: 13%;">
                <div class="text-center" style="padding: 0px 0px">
                	{{-- <select id="sliderPriority" data-id="{{ $slider->id }}" class="form-control pull-left" style="margin-left: 5px; width:50%">
                		@foreach( $priorityList as $priority)
                			@if($slider->priority == $priority->priority)
	                			<option selected value="{{ $priority->priority }}">{{ $priority->priority }}</option>
                			@else
	                			<option value="{{ $priority->priority }}">{{ $priority->priority }}</option>
                			@endif
	                	@endforeach
                	</select> --}}
                	{{-- <button style="margin-left: 5px; padding:7px;" id="showOrders" class="btn btn-primary btn-xs pull-left" data-action="showOrders" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-folder-open"></span></button> --}}

                	<button style="margin-left: 5px; padding:7px;" id="edit" class="btn btn-warning btn-xs pull-left" data-action="edit" data-id="{{ $slider->id }}" ><span class="glyphicon glyphicon-edit"></span></button>

                	@if( $slider->is_active )
                		<button style="margin-left: 5px; padding:7px;" id="activate" class="btn btn-success btn-xs pull-left" data-action="activate" data-id="{{ $slider->id }}" ><span class="glyphicon glyphicon-eye-open"></span></button>
                	@else
                		<button style="margin-left: 5px; padding:7px;" id="unActivate" class="btn btn-danger btn-xs pull-left" data-action="unActivate" data-id="{{ $slider->id }}" ><span class="glyphicon glyphicon-eye-close"></span></button>
                	@endif

                    <button style="padding:7px;" id="delete" class="btn btn-primary btn-xs" data-action="delete" data-id="{{ $slider->id }}" ><span class="glyphicon glyphicon-trash"></span></button>
				</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- <ul >
    <li>1</li>
    <li>2</li>
    <li>3</li>
</ul> --}}

<div class="pull-right">{{ $sliders->links() }}</div>