<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * Referencing to table in DB
     * 
     * @var string
     */
    protected $table = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'path', 'thumbnail_path',
        'priority'
    ];

    /**
     *  Images belongs to a Product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product()
    {
    	return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
