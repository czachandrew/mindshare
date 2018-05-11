<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['address_1', 'address_2', 'city', 'state', 'zip', 'country', 'attention_contact_id', 'role', 'company_id'];

    public function company(){
    	return $this->belongsTo('App\Company');
    }

    public function attention(){
    	return $this->belongsTo('App\Contact', 'attention_contact_id');
    }
}
