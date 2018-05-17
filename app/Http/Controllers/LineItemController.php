<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LineItem;
use App\Quote;

class LineItemController extends Controller
{
    public function __construct(){

    }

    public function index(){

    }

    public function update(LineItem $lineitem, Request $request){
    	$lineitem->update($request->all());
    	return $lineitem;
    }

    public function delete(LineItem $lineitem){
    	$lineitem->forceDelete();
    	return 'success';
    }
}
