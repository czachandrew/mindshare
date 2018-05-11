<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer; 
use App\Company;
use App\Rep;
use App\Contact;
use DB;
use Auth; 
use Log;

class CustomerController extends Controller
{
    public function __construct(){

    }

    public function show(){
    	return view('customer');
    }

    public function create(Request $request){
    	$customer = Customer::create($request->all());
    	return $customer; 
    }

    public function lookup($term){

    	$companies = Company::where('name', 'like', '%' . $term . '%')->limit(5)->get();
    	
    	$reps = Rep::where('first_name', 'like', '%' . $term . '%')->orWhere('last_name', 'like', '%' . $term . '%')->with(['reseller','reseller.billing_address', 'reseller.shipping_address'])->limit(5)->get();
    	//Log::info($reps);
    	//Log::info($companies);
    	$results = [];
    	foreach($reps as $rep){
    		Log::info($rep);
    		$result['name'] = $rep->first_name . ' ' . $rep->last_name;
    		$result['id'] = $rep->id;
    		$result['type'] = 'rep';
    		$result['description'] = $rep->email . " " . $rep->reseller->name . "Rep";
    		$result['object'] = $rep->reseller;
    		$results[] = $result;
    	}
    	$result = [];

    	foreach($companies as $company){
    		$result['name'] = $company->name;
    		$result['id'] = $company->id;
    		$result['description'] = $company->website;
    		$result['type'] = 'company';
    		$result['object'] = $company;
    		$results[] = $result;
    	}
    	Log::info($results);


    	usort($results, function($a, $b){
    		return strcmp($a["name"], $b["name"]);
    	});

    	return $results;
    }

    


    public function details($customer){
    	$customer = $customer->load(['company','mainContact','owner']);
    	return $customer; 
    }

    public function list(Request $request){
    	$user = Auth::user();
    	$customers = $user->customers;

    }
}
