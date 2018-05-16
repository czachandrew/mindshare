<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Company;
use Storage; 
use Excel; 
use Log;
use Auth;

class ContactController extends Controller
{
    public function __construct(){

    }

    public function show(){
    	return view('contact');
    }

    public function create(Request $request){
    	$contact = Contact::create($request->all());
    	return $contact;
    }

    public function claim(Company $company){
    	$user = Auth::user();
    	$company->user_id = $user->id;
    	$company->save();
    	return;
    }

    public function update(Contact $contact, Request $request){
        $contact->update($request->all());
        return $contact;
    }

    public function importContacts(Request $request){
    	ini_set('max_execution_time', '180');
    	$file = $request->file('file');
    	$name = 'TestContacts';
    	//return 'hello';
    	$ext = $file->getClientOriginalExtension();
    	$new = Storage::putFileAs('/public/uploads', $file, $name . '.'.$ext);
    	$path = 'storage/app/public/uploads/' . $name .'.'.$ext;
    	$tarzan = [];
    	Log::info($path);
    	Excel::filter('chunk')->load($path)->chunk(250, function($results) use (&$tarzan){
    		foreach($results as $row){
    			Log::info($row);
    			$company = Company::where('cdw_id', $row->customersid_customer_cdw)->first();
    			if($company){
    				//then we can create the contact and attach
    				$names = explode(' ', $row->euc_name_1);
    				$contact = [
    					'first_name' => $names[0],
    					'phone' => $row->euc_phone_1,
    					'email' => $row->euc_email_address,
    					'title' => $row->euc_contact_title
    				];

    				$check = []; 
    				$check['first_name'] = $contact['first_name'];
    				if(array_key_exists(1, $names)){
    					$contact['last_name'] = $names[1];
    					$check['last_name'] = $names[1];
    				}
    				$newCon = Contact::firstOrNew($check,$contact);
    				$company->contacts()->save($newCon);
    			}
    			array_push($tarzan, $row);
    		}
    		//return true; 
    	}, false);

    	return $tarzan;
    }
}
