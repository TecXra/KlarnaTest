<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarData extends Model
{
	/**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'car_data';

    public function order()
    {
    	return $this->belongsTo('App\Order', 'order_id', 'id');
    }
}
