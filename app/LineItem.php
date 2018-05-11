<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    protected $fillable = ['part_id','quantity','sale_price','lineable_id','lineable_type'];

    protected $with = ['part'];

    public function part(){
    	return $this->belongsTo('App\Part');
    }

    public function lineable(){
  		return $this->morphTo();
    }
}
