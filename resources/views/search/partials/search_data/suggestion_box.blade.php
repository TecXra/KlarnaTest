<div class="row">
	<div class="col-sm-12">
		<ul>
		@foreach($suggestedProducts as $product)
			@if($product->product_category_id == 2)
				<li><a href="{{ asset($product->productUrl().'/pcd/') }}"><img height=35 src="{{ asset($product->productImages->first()->path) }}"> {{ $product->product_name }}</a></li>
			@else
				<li><a href="{{ asset($product->productUrl()) }}"> {{ $product->product_name }}</a></li>
			@endif
		@endforeach
			<li style="text-align: center;"><a id="search-all-btn" class=" btn" href="{{ asset('sok_sortimentet?') }}">Visa Alla Resultat</a></li>
		</ul>
	</div>
</div>