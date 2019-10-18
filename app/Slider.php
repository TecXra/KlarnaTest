<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

    public function page()
    {
    	return $this->belongsTo('App\Page', 'page_id', 'id');
    }
}
