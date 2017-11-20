<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
	use SoftDeletes;

    protected $table = 'suppliers';
	protected $dates = ['deleted_at'];

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['name', 'cnpj', 'address'];

    public function products(){
    	return $this->hasMany(Product::class);
    }
}
