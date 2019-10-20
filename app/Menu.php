<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';


    public function pages()
    {
        return $this->belongsToMany('App\Page')
		        ->withPivot('priority')
		    	->withTimestamps();
    }
}
