<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    protected $fillable = ['name','website','phone','billing_address_id','shipping_address_id'];

    public function reps(){
    	return $this->hasMany('App\Rep');
    }

    public function customers(){
    	return $this->belongsToMany('App\Customer');
    }

    public function billing_address(){
    	return $this->belongsTo('App\Address', 'billing_address_id');
    }

    public function shipping_address(){
    	return $this->belongsTo('App\Address', 'shipping_address_id');
    }
}
