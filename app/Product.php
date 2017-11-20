<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
    protected $fillable = ['supplier_id','name', 'description', 'cost', 'quantity'];

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }
}
