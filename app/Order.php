<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	/**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'orders';


	public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function orderStatus()
    {
        return $this->belongsTo('App\OrderStatus', 'order_status_id', 'id');
    }

    public function deliveryMethod()
    {
        return $this->belongsTo('App\DeliveryMethod', 'delivery_method_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id', 'id');
    }

    public function orderDetails()
    {
    	return $this->hasMany('App\OrderDetail', 'order_id', 'id');
    }

    public function orderComments()
    {
        return $this->hasMany('App\OrderComment', 'order_id', 'id');
    }    

    public function carData()
    {
    	return $this->hasOne('App\CarData', 'order_id', 'id');
    }

    public function totalPriceProducts()
    {
        return $this->total_price_including_tax - $this->shipping_fee + $this->discount;
    }


    
    // public function create($create)
    // {
    // 	$order = new Order;
   
    // }
}
