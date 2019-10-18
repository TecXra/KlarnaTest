<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $table = 'profits';

    public function products()
    {
    	return $this->hasMany('App\Product', 'profit_id', 'id');
    }

}
