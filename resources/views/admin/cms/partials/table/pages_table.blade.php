<span id="userCount" >Listar {{ $pages->total() }} sidor</span>
<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th style="width: 5%;">Id</th>
            <th style="width: 80%;">Namn</th>
            <th style="width: 15%;">Hantera</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Id</th>
            <th>Namn</th>
            <th>Hantera</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>
                <td>{{ $page->label }}</td>
                <td>
                <div class="text-center" style="padding: 0px 0px">
                	{{-- <select id="pagePriority" data-id="{{ $page->id }}" class="form-control pull-left" style="margin-left: 5px; width:50%">
                		@foreach( $priorityList as $priority)
                			@if($page->priority == $priority->priority)
	                			<option selected value="{{ $priority->priority }}">{{ $priority->priority }}</option>
                			@else
	                			<option value="{{ $priority->priority }}">{{ $priority->priority }}</option>
                			@endif
	                	@endforeach
                	</select> --}}
                	{{-- <button style="margin-left: 5px; padding:7px;" id="showOrders" class="btn btn-primary btn-xs pull-left" data-action="showOrders" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-folder-open"></span></button> --}}

                	<button style="margin-left: 5px; padding:7px;" id="edit" class="btn btn-warning btn-xs pull-left" data-action="edit" data-id="{{ $page->id }}" ><span class="glyphicon glyphicon-edit"></span></button>

                	@if( $page->is_active )
                		<button style="margin-left: 5px; padding:7px;" id="activate" class="btn btn-success btn-xs pull-left" data-action="activate" data-id="{{ $page->id }}" ><span class="glyphicon glyphicon-eye-open"></span></button>
                	@else
                		<button style="margin-left: 5px; padding:7px;" id="unActivate" class="btn btn-danger btn-xs pull-left" data-action="unActivate" data-id="{{ $page->id }}" ><span class="glyphicon glyphicon-eye-close"></span></button>
                	@endif
                    @if( $page->is_removable )
                        <button style="padding:7px;" id="delete" class="btn btn-primary btn-xs" data-action="delete" data-id="{{ $page->id }}" ><span class="glyphicon glyphicon-trash"></span></button>
                    @endif
				</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{{ $pages->links() }}</div>