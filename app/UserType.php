<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    /**
     * Referencing to table in DB
     * 
     * @var string
     */
    protected $table = 'user_type';

    public function users()
    {
    	return $this->hasMany('App\User', 'user_type_id', 'id');
    }
}
