<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
	/**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'delivery_methods';

    public function orders()
    {
        return $this->hasMany('App\Order', 'delivery_method_id', 'id');
    }
}
