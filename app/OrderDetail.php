<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'order_details';

	public function order()
    {
    	return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
