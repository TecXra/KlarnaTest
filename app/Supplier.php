<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * Referencing to table in DB
     * 
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     *  Supplier has many products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mainSupplierProducts()
    {
    	return $this->hasMany('App\Product', 'main_supplier_id');
    }

    /**
     *  Supplier has many products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subSupplierProducts()
    {
    	return $this->hasMany('App\Product', 'sub_supplier_id');
    }
}
