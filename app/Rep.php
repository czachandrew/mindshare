<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rep extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'reseller','reseller_id']; 

    public function customers(){
    	return $this->belongsToMany('App\Customer');
    }

    public function reseller(){
    	return $this->belongsTo('App\Reseller','reseller_id');
    }
}
