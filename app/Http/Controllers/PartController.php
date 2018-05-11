<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Part; 
use Auth;
//really we may not need auth except for create

class PartController extends Controller
{
    public function __construct(){

    }

    public function show(){
    	return view('part');
    }

    public function create(Request $request){
    	//determine if this use is authrized to create parts
    	$user = Auth::user();
    	if($user){
    		//ok this is a logged in user go ahead and create
    		$part = Part::create($request->all());
    		return $part;
    	} else {
    		//return an error there is not authenticated user associated with this request
    	}
    }

    public function list(){
    	$parts = Part::all();
    	return $parts;
    }

    public function lookup($term){
    	$parts = Part::where('part_number', 'like', '%' . $term . '%')->orWhere('description' ,'like', '%' . $term . '%')->limit(10)->get();

    	return $parts;
    }
}
