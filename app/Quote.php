<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['title','good_until','contact_id','lead_id','customer_id','owner_id','value','status', 'billing_address_id','shipping_address_id', 'shipping_method', 'shipping_id','company_id','shipping_cost', 'ref_number'];

    protected $with = ['company','lineitems', 'shippingAddress', 'billingAddress'];

    public function lead(){
    	return $this->belongsTo('App\Lead');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function customer(){
    	return $this->belongsTo('App\Customer');
    }

    public function contact(){
    	return $this->belongsTo('App\Contact');
    }

    public function owner(){
    	return $this->belongsTo('App\User');
    }

    public function shippingAddress(){
    	return $this->belongsTo('App\Address', 'shipping_address_id');
    }

    public function billingAddress(){
    	return $this->belongsTo('App\Address','billing_address_id');
    }

    public function lineitems(){
    	return $this->morphMany('App\LineItem','lineable');
    }

    public function addLineItem($item){
        return $this->lineitems()->create($item);
    }

    public function notes(){
        return $this->morphMany('App\Note', 'noteable');
    }
}
