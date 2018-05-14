<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $fillable = ['name', 'website', 'shipping_address_1','shipping_address_2','shipping_city', 'shipping_state','shipping_zip','shipping_country','billing_address_1','billing_address_2','billing_city','billing_state','billing_zip','billing_country', 'historic_value', 'primary_shipping_id','primary_billing_id','cdw_id'];

	protected $with = ['latestaction','followups','primaryShipping','primaryBilling'];

	public function contacts(){
		return $this->hasMany('App\Contact');
	}

	public function primaryShipping(){
		return $this->belongsTo('App\Address', 'primary_shipping_id');
	}

	public function primaryBilling(){
		return $this->belongsTo('App\Address', 'primary_billing_id');
	}

	public function addresses(){
		return $this->hasMany('App\Address');
	}

	public function addAddress($address){
		return $this->addresses()->create($address);
	}

	public function notes(){
		return $this->morphMany('App\Note','noteable');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function activities(){
		return $this->morphMany('App\Activity', 'activitable');
	}

	public function tasks(){
		return $this->morphToMany('App\Task', 'taskable');
	}

	public function latestaction(){
		return $this->activities()->limit(1)->latest();
	}

	public function followups(){
		return $this->tasks()->where('type', 'Followup');
	}

	public function scopeWithNotes($query){
		return $query->with('notes');
	}

	public function reps(){
		return $this->belongsToMany('App\Company', 'company_rep','rep_id','company_id');
	}
}
