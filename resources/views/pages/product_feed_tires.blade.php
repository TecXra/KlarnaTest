<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<Tires>
@foreach($products as $product)
<Product>
  <Productname>{{ $product->product_name }}</Productname>
  <Articlenr>{{ $product->main_supplier_id }}-{{ $product->main_supplier_product_id }}</Articlenr>
  <Category>{{ $product->productType->label }}</Category>
  <Price>{{ $product->webPrice() }} kr</Price>
  <Producturl>{{ url($product->productUrl()) }}</Producturl>
  <Productdescription>{{ $product->code }}</Productdescription>
  <Imageurl>{{ asset($product->productImages()->first()->path)  }}</Imageurl>
  <Quantity>{{ $product->quantity <= 20 ? $product->quantity : '+20' }}</Quantity>
  <Shippingcost>100 kr</Shippingcost>
  <Brand>{{ $product->product_brand }}</Brand>
  <Model>{{ $product->product_model }}</Model>
</Product>
@endforeach
</Tires>
