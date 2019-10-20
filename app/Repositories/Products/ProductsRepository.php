<?php

namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Collection;

interface ProductsRepository
{
     public function search(string $query = "", $size): Collection;
    
    // public function searchSuggestedProducts(string $query = ""): Collection;
}
