<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['part_number', 'description','cost','suggested_price','floor','image','status','category'];

    public function lineItems(){
    	return $this->hasMany('App\LineItem');
    }
}
