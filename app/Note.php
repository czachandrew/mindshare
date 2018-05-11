<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['content', 'author_id', 'noteable_id', 'noteable_type'];

    protected $with = ['author'];

    public function noteable(){
    	return $this->morphTo();
    }

    public function author(){
    	return $this->belongsTo('App\User','author_id');
    }
}
