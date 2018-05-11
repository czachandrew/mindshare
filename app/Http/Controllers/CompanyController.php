<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Address;
use Auth;

class CompanyController extends Controller
{
    public function __construct(){

    }

    public function show(Company $company){
    	return view('company')->with('company', $company->load(['notes', 'contacts','activities.tasks', 'tasks']));
    }

    public function createAddress(Request $request){
    	//check and see if something with a similar address_1 exists
    	$address = Address::where('address_1', $request->address_1)->first(); 
    	if($address !== null){
    		$address = Address::create($request->all());
    		return ['message' => 'created', 'address' => $address];
    	} else {
    		return ['message' => 'exists', 'address' => $address];
    	}
    	
    }

    public function addAddress(Company $company, Request $request){
        //check to make sure that the current address doesn't already exist
        $company->addAddress($request->all());
        return $company->addresses()->latest()->first();

    }

    public function new(){
        return view('newcompany');
    }

    public function create(Request $request){
    	$company = Company::create($request->all());
        //now let's create the addresses 
        $billingAddress = [
            'address_1' => $company->billing_address_1,
            'address_2' => $company->billing_address_2,
            'city' => $company->billing_city,
            'state' => $company->billing_state, 
            'zip' => $company->billing_zip,
            'country' => "United States",
            'role' => 'billing'
        ];

        $shippingAddress = [
            'address_1' => $company->shipping_address_1,
            'address_2' => $company->shipping_address_2,
            'city' => $company->shipping_city,
            'state' => $company->shipping_state, 
            'zip' => $company->shipping_zip,
            'country' => "United States",
            'role' => 'shipping'
        ];

        $company->addAddress($billingAddress);
        $company->primary_billing_id = $company->addresses()->latest()->first()->id;
        $company->addAddress($shippingAddress);
        $company->primary_shipping_id = $company->addresses()->latest()->first()->id;
        //need to create their address records 
        $company->save();
    	return $company;
    }

    public function addressFix(){
        $companies = Company::all();
        foreach($companies as $company){
            $billingAddress = [
            'address_1' => $company->billing_address_1,
            'address_2' => $company->billing_address_2,
            'city' => $company->billing_city,
            'state' => $company->billing_state, 
            'zip' => $company->billing_zip,
            'country' => "United States",
            'role' => 'billing'
        ];

        $shippingAddress = [
            'address_1' => $company->shipping_address_1,
            'address_2' => $company->shipping_address_2,
            'city' => $company->shipping_city,
            'state' => $company->shipping_state, 
            'zip' => $company->shipping_zip,
            'country' => "United States",
            'role' => 'shipping'
        ];

        $company->addAddress($billingAddress);
        $company->primary_billing_id = $company->addresses()->latest()->first()->id;
        $company->addAddress($shippingAddress);
        $company->primary_shipping_id = $company->addresses()->latest()->first()->id;
        //need to create their address records 
        $company->save();
        // /return $company;
        }
    }

    public function lookup(Request $request){
    	$term = $request->q;
    	$companies = Company::where('name', 'like', '%' . $term . '%')->limit(10)->get();
    	return json_encode($companies);
    }

    public function addressLookup($term){
    	$addresses = Address::where('address_1','like','%' . $term . '%')->orWhere('address_2','like', '%' .  $term . '%')->orWhere('city', 'like', '%' .  $term . '%')->limit(10)->get();
    	return $addresses;
    }

    public function secondaryLookup($term){
    	$companies = Company::where('name', 'like', '%' . $term . '%')->limit(10)->get();
    	return $companies;
    }

    public function list(){
    	$user = Auth::user();
    	$companies = $user->company;
    	return $companies;
    }
}
