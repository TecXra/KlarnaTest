<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    protected $table = 'order_comments';

    public function order()
    {
    	return $this->belongsTo('App\Order', 'order_id', 'id');
    }

}
