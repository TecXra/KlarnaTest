<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'payment_methods';

    public function orders()
    {
        return $this->hasMany('App\Order', 'payment_method_id', 'id');
    }
}
