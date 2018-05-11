<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['title','customer_id','owner_id','quote_id','value','status','shipping_address_id','billing_address_id'];

    public function quote(){
    	return $this->belongsTo('App\Quote');
    }

    public function owner(){
    	return $this->belongsTo('App\User');
    }
    public function lineitems(){
    	return $this->morphMany('App\LineItem', 'lineable');
    }
    
    public function shippingAddress(){
    	return $this->belongsTo('App\Address', 'shipping_address_id');
    }

    public function billingAddress(){
    	return $this->belongsTo('App\Addresss', 'billing_address_id');
    }
}
