<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favorite;

class FavoriteController extends Controller
{
    //

	public function __construct(){

	}

	public function create(Request $request){
		//I need to know what the favorite is and who is creating it
		//create a favorite for this user
		//associate the record
		$favorite = Favorite::create($request->all());
		return $favorite;
	}

	public function remove(Favorite $favorite){
		$favorite->delete();
		return 'success';
	}
}
