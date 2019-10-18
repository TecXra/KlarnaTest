<span id="productCount" >Listar {{ $products->total() }} produkter</span>
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produkt namn</th>
            <th>Produkt typ</th>
            <th>Mått</th>
            <th>Antal</th>
            <th>Pris</th>
            <th>Åtgärd</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Produkt namn</th>
            <th>Produkt typ</th>
            <th>Mått</th>
            <th>Antal</th>
            <th>Pris</th>
            <th>Åtgärd</th>
        </tr>
    </tfoot>
    <tbody>

    	@foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->productType->label }}</td>
                <td>
                	{{ $product->product_dimension }}
                	{{ !empty($product->product_inner_dimension) ? "- ".$product->product_inner_dimension: "" }}
                </td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->price }} kr</td>
	            <td >
                	<button style="padding:7px;" id="edit" class="btn btn-warning btn-xs" data-action="edit" data-id="{{ $product->id }}" ><span class="glyphicon glyphicon-edit"></span></button>
                	@if( $product->is_shown)
                		<button style="padding:7px;" id="show" class="btn btn-success btn-xs" data-action="show" data-id="{{ $product->id }}" ><span class="glyphicon glyphicon-eye-open"></span></button>
                	@else
                		<button style="padding:7px;" id="dontShow" class="btn btn-danger btn-xs" data-action="dontShow" data-id="{{ $product->id }}" ><span class="glyphicon glyphicon-eye-close"></span></button>
                	@endif
					<button style="padding:7px;" id="delete" class="btn btn-primary btn-xs" data-action="delete" data-id="{{ $product->id }}" ><span class="glyphicon glyphicon-trash"></span></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{{ $products->links() }}</div>