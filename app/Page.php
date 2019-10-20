<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    public function menus()
    {
        return $this->belongsToMany('App\Menu')
        		->withPivot('priority')
		    	->withTimestamps();
    }

    public function sliders()
    {
        return $this->hasMany('App\Slider', 'page_id', 'id');
    }
}
