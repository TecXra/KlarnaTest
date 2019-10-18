<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSearch extends Model
{
    /**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'customer_searches';
}
