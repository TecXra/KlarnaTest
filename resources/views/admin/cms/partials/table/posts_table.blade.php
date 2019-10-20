<span id="userCount" >Listar {{ $posts->total() }} inlägg</span>
<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th style="width: 5%;">Id</th>
            <th style="width: 80%;">Titel</th>
            <th style="width: 15%;">Hantera</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Id</th>
            <th>Titel</th>
            <th>Hantera</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->label }}</td>
                <td>
                <div class="text-center" style="padding: 0px 0">
                	<button style="margin-left: 5px; padding:7px;" id="edit" class="btn btn-warning btn-xs pull-left" data-action="edit" data-id="{{ $post->id }}" ><span class="glyphicon glyphicon-edit"></span></button>

                	@if( $post->is_active)
                		<button style="margin-left: 5px; padding:7px;" id="activate" class="btn btn-success btn-xs pull-left" data-action="activate" data-id="{{ $post->id }}" ><span class="glyphicon glyphicon-eye-open"></span></button>
                	@else
                		<button style="margin-left: 5px; padding:7px;" id="unActivate" class="btn btn-danger btn-xs pull-left" data-action="unActivate" data-id="{{ $post->id }}" ><span class="glyphicon glyphicon-eye-close"></span></button>
                	@endif

                    <button style="padding:7px;" id="delete" class="btn btn-primary btn-xs" data-action="delete" data-id="{{ $post->id }}" ><span class="glyphicon glyphicon-trash"></span></button>
				</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{{ $posts->links() }}</div>