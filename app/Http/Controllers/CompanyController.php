<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Address;
use App\Rep;
use App\User;
use Storage;
use Excel;
use Log;

use Auth;

class CompanyController extends Controller
{
    public function __construct(){

    }

    public function show(Company $company){
    	return view('company')->with('company', $company->load(['notes', 'contacts','activities.tasks', 'tasks','quotes','favorite']));
    }

    public function claim(Company $company){
        $user = Auth::user();
        $company->user_id = $user->id;
        $company->save();
        return $company->load('contacts','tasks');
    }

    public function drop(Company $company){
        $user = Auth::user();
        $company->user_id = NULL; 
        $company->save();
        return $company->load('contacts','tasks');
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

    public function assign(Request $request){
        ini_set('max_execution_time', '180');
        $file = $request->file('file');
        $col = $request->col;
        $user = $request->user;
        $name = 'TestAssign';
        $ext = $file->getClientOriginalExtension();
        //$type = $this->getType($ext);
        $new = Storage::putFileAs('/public/uploads', $file, $name .'.'.$ext);
        //load the excel file and create some companies
        $path = 'storage/app/public/uploads/'. $name .'.'.$ext;
        $tarzan = []; 
        $i = 1; 
        Excel::filter('chunk')->load($path)->chunk(250, function($results) use (&$tarzan, &$user, &$col) {
                foreach($results as $row){
                    //get the company
                    $company = Company::where('cdw_id',$row[$col])->first();
                    if($company){
                        $company->user_id = $user;
                        $company->status = 'active';
                        $company->save();
                    }
                    //set the user_id 
                    //save
                }
            }, false);
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
        $filter = request('filter');
        $limit = request('limit');
        $perPage = request('per_page');
        //i need to know if we are limit to users or searching all companies
        if($limit === 'user'){
            //I only want companies that are assigned to the current user
            $request = $user->companies();
            if($filter){
                $request->where('name', 'LIKE','%'.$filter.'%');
            }
            return $request->paginate($perPage);
        } else if($limit === 'fav') {
            $all = collect();
            $result = $user->favorites->load('favoritable')->each(function ($company) use (&$all) {
                    $all->push($company->favoritable);
                });
            return ['data' => $all];

        }else {
            if($filter){
                return $request = Company::where('name', 'LIKE', '%'.$filter .'%')->paginate($perPage);
            } else {
                return Company::paginate($perPage);
            }
        }

    }

    public function showImportForm(){
        $users = User::all();
        return view('importcustomers',['users' => $users]);
    }

    public function importCompanies(Request $request){
        
        ini_set('max_execution_time', '180');
        $file = $request->file('file');
        //return $request->all();
        $name = 'TestName';
        $ext = $file->getClientOriginalExtension();
        //$type = $this->getType($ext);
        $new = Storage::putFileAs('/public/uploads', $file, $name .'.'.$ext);
        //load the excel file and create some companies
        $path = 'storage/app/public/uploads/'. $name .'.'.$ext;
        $tarzan = []; 
        $i = 1; 
        Excel::filter('chunk')->load($path)->chunk(250, function($results) use (&$tarzan, &$i)
        {
            //$i = 1;
            $keeper = [];
            foreach($results as $row)
            {
                // do stuff
                //Log::info($row->euc_conπtact_title);
                if($row->cust_company_name_1 !== null){
                    //array_push($tarzan, $row->euc_contact_title);
                    //create the object
                    $company = [
                        'name' => $row->cust_company_name_1,
                        'website' => substr($row->cust_www_address, 0, 180),
                        'shipping_address_1' => $row->cust_address_1,
                        'shipping_address_2' => $row->cust_address_2,
                        'shipping_city' => $row->cust_city_1,
                        'shipping_state' => $row->cust_state_1,
                        'shipping_zip' => $row->cust_zip_1,
                        'shipping_country' => 'United States',
                        'cdw_id' => $row->id_customer_cdw,
                        'historic_value' => $row->z_salespc5alltime_cn,
                        'billing_address_1' => $row->cust_address_1,
                        'billing_address_2' => $row->cust_address_2,
                        'billing_city' => $row->cust_city_1,
                        'billing_state' => $row->cust_state_1,
                        'billing_zip' => $row->cust_zip_1,
                        'billing_country' => 'United States',
                        'cdw_id' => $row->id_customer_cdw,
                    ];

                    if($row->cust_address_1){
                        $shipping = [
                        'address_1' => $row->cust_address_1,
                        'address_2' => $row->cust_address_2,
                        'city' => $row->cust_city_1,
                        'state' => $row->cust_state_1,
                        'zip' => $row->cust_zip_1,
                        'country' => 'United States',
                        'role' => 'shipping'
                    ];


                    $billing = [
                        'address_1' => $row->cust_address_1,
                        'address_2' => $row->cust_address_2,
                        'city' => $row->cust_city_1,
                        'state' => $row->cust_state_1,
                        'zip' => $row->cust_zip_1,
                        'country' => 'United States',
                        'role' => 'billing'
                    ];
                    Log::info($company);
                    Log::info($shipping);
                    Log::info($billing);

                    $newShip = Address::firstOrCreate(['address_1' => $shipping['address_1']],$shipping);

                    $newBill = Address::firstOrCreate(['address_1' => $billing['address_1']], $billing);

                    $company['primary_shipping_id'] = $newShip->id;
                    $company['primary_billing_id'] = $newBill->id;
                    }

                    

                    $newCo = Company::firstOrCreate(['name' => $company['name']],$company);

                    //Log::info($row);

                    if($row->cust_address_1){

                        $newCo->addresses()->save($newShip);
                        $newCo->addresses()->save($newBill);
                    }



                    if($row->vr_rep_name !== null){
                        //Log::info('rep found');
                        Log::info($row->vr_rep_name);
                        $names = explode(' ', $row->vr_rep_name);
                        Log::info($names);
                        if(array_key_exists(1, $names)){
                            $rep = [
                            'first_name' => $names[0],
                            'last_name' => $names[1],
                            'email' => $row->vr_vendor_rep_email_owns_this_end_user_account,
                            'phone' => $row->vr_vendor_rep_phone1,
                            'reseller_id' => 1
                        ];
                        $newRep = Rep::firstOrCreate(['email' => $rep['email']],$rep);
                        $newCo->reps()->sync([$newRep->id]);
                        }
                        
                    }

                    array_push($tarzan, $row->cust_company_name_1);

                }
                //return true;
            }

            //return true;
            //$obj = $results->take(1);
            //$tarzan[] = $obj->euc_contact_title;
            //Log::info($tarzan);

            //return $obj; 
             Log::info('Chunk ' . $i . ' is done');
                $i++;
                //return $keeper;
        }, false); 

         /** $obj = Excel::load($path, function($reader){
            $reader->takeRows(2);
            
        })->get(); **/
        Log::info($tarzan);
        return ['res' => $tarzan];
    }
}
