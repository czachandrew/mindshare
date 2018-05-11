<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reseller;

class ResellerController extends Controller
{
    public function create(Request $request){
    	//check and see that the reseller doesn't already exist

    	//create
    	$reseller = Reseller::create($request->all());

    	return $reseller;
    }

    public function update(Reseller $reseller, Request $request){
    	
    }
}
