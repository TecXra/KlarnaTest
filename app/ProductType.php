<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    /**
     * Referencing to table in DB
     * 
     * @var string
     */
    protected $table = 'product_type';

    public function products()
    {
    	return $this->hasMany('App\Product', 'product_type_id', 'id');
    }

    public function shippingCost()
    {
        return $this->hasOne('App\ShippingCost', 'product_type_id', 'id');
    }
}
