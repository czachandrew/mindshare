<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;
use App\Company;
use App\LineItem;
use Log;
use PDF;

class QuoteController extends Controller
{
    public function __construct(){

    }

    public function show(Quote $quote){
    	//let's decided edit or create 

    	return view('quote', ['company' => $quote->company, 'quote' => $quote, 'start' => 'edit']);
    }

    public function download(){
    	$quote = Quote::with('lineitems', 'shippingAddress','billingAddress', 'company','owner')->find(17);
    	Log::info($quote);
    	//return view('printquote', ['quote' => $quote]);
    	$pdf = PDF::loadView('printquote', ['quote' => $quote]);
    	return $pdf->download('Quote ' . $quote->ref_number .'.pdf');

    }

    public function create(Company $company){
    	return view('quote', ['company' => $company, 'quote' => '', 'start' => 'create']);
    }

    public function save(){
    	$quoteData = request('quote');
    	$lineitems = request('lineitems');

    	$quote = Quote::create($quoteData);

    	$quote->ref_number = $quote->id + 1000; 
    	$quote->title = $quote->title . "#" . $quote->ref_number;

    	$quote->save();

    	foreach($lineitems as $lineitem){
    		$quote->addLineItem($lineitem);
    	}

    	return $quote->load(['lineitems', 'company', 'shippingAddress', 'billingAddress']);
    }
}
