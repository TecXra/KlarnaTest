<span id="userCount" >Listar {{ $users->total() }} produkter</span>
<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th style="width: 5%;">Id</th>
            <th style="width: 20%;">Kund</th>
            <th style="width: 20%;">E-post</th>
            <th style="width: 15%;">Adress</th>
            <th style="width: 15%;">Lev. adress</th>
            <th style="width: 20%;">Hantera</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Id</th>
            <th>Kund</th>
            <th>E-post</th>
            <th>Adress</th>
            <th>Lev. adress</th>
            <th>Hantera</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}<br>
                	{{ $user->ssn }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->billing_full_name }} <br>
					{{ $user->billing_street_address }}<br>
					{{ $user->billing_postal_code }}, {{ $user->billing_city }}<br>
					{{ $user->billing_country }}
                </td>
                <td>{{ $user->shipping_full_name }} <br>
					{{ $user->shipping_street_address }}<br>
					{{ $user->shipping_postal_code }}, {{ $user->shipping_city }}<br>
					{{ $user->shipping_country }}<br>
                </td>
                <td>
                <div class="text-center" style="padding: 23px 0">
                	<select id="userType" data-id="{{ $user->id }}" class="form-control pull-left" style="margin-left: 5px; width:50%">
                		@foreach( $userTypes as $userType)
                			@if($user->user_type_id == $userType->id)
	                			<option selected value="{{ $userType->id }}">{{ $userType->label }}</option>
                			@else
	                			<option value="{{ $userType->id }}">{{ $userType->label }}</option>
                			@endif
	                	@endforeach
                	</select>
                	{{-- <button style="margin-left: 5px; padding:7px;" id="showOrders" class="btn btn-primary btn-xs pull-left" data-action="showOrders" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-folder-open"></span></button> --}}

                	<button style="margin-left: 5px; padding:7px;" id="edit" class="btn btn-warning btn-xs pull-left" data-action="edit" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-edit"></span></button>

                	@if( $user->is_active )
                		<button style="margin-left: 5px; padding:7px;" id="activate" class="btn btn-success btn-xs pull-left" data-action="activate" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-eye-open"></span></button>
                	@else
                		<button style="margin-left: 5px; padding:7px;" id="unActivate" class="btn btn-danger btn-xs pull-left" data-action="unActivate" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-eye-close"></span></button>
                	@endif

					<a role="button" style="margin-left: 5px; padding:7px;" href="{{ url('admin/loginUser/' . $user->id) }}"id="logIn" class="btn btn-primary btn-xs pull-left" data-action="logIn" data-id="{{ $user->id }}" ><span class="glyphicon glyphicon-log-in"></span></a>
				</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{{ $users->links() }}</div>