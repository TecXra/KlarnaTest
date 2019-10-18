<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
	/**
     * Referencing to table in DB
     * 
     * @var string
     */
    protected $table = 'shipping_cost';

    public function productType()
    {
    	return $this->belongsTo('App\ProductType', 'product_type_id', 'id');
    }
}
