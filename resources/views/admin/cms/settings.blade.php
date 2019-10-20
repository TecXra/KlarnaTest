@extends('admin_layout')

@section('content')

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
        			<h1 class="pull-left">Hantera inställningar</h1>
        		</div>
        	</div>

        	<br>

        	<div class="row">
                
        		<div id="dataTable" class="col-sm-6">
                    <form action="{{ url('admin/cms/updateSettings') }}" method="POST">
                        {{ csrf_field() }}
        			     @include('admin/cms/partials/table/settings_table')
                    </form>
        	    </div>
        	</div>

        </div>
    </div>

</div>
@endsection

@section('footer')

<script src="{{ asset('assets/js/dropzone.js') }}"></script>

<script type="text/javascript">


</script>

@endsection