<?php

namespace App\Repositories\Products;

use App\Product;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductsRepository implements ProductsRepository
{
    public function search(string $searchTxt = "", $size): Collection
    {
        return Product::where(function($query) use($searchTxt, $size) {
            	return $query
                    ->where('product_name', 'LIKE', "%{$searchTxt}%")
                    ->orWhere('main_supplier_product_id', $searchTxt);
            })
            ->where('is_shown', 1)
            ->where('is_deleted', 0)
            ->limit($size)
            ->get();
            // ->paginate(24);
    }

    // public function searchSuggestedProducts(string $searchTxt = ""): Collection
    // {
    //     return Product::where(function($query) use($searchTxt) {
    //             return $query
    //                 ->where('product_name', 'LIKE', "%{$searchTxt}%")
    //                 ->orWhere('main_supplier_product_id', $searchTxt);
    //         })
    //         ->where('is_shown', 1)
    //         ->where('is_deleted', 0)
    //         ->limit(5)
    //         ->get();
    //         // ->paginate(24);
    // }
}