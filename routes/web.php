<?php
use App\Events\UserWasMentioned;
use App\Notifications\YouWereMentioned;
use App\User;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@show');

Route::get('/eventest', function(){
	UserWasMentioned::dispatch('Andy', '<p>Wonder how this is serialized</p>');
});

Route::get('/fixaddress', 'CompanyController@addressFix');

Route::get('/notification/{user}', function(User $user){
	print_r($user);
	$user->notify(new YouWereMentioned);
});

Route::get('/tasks/{task}', 'TaskController@show');

Route::get('/home', 'HomeController@show');

Route::get('/customer', 'CustomerController@show');

Route::get('/quotes/{quote}/pdf', 'QuoteController@download');

Route::get('/company', 'CompanyController@show');

Route::get('/companies/import', 'CompanyController@showImportForm');

Route::post('/companies/import', 'CompanyController@importCompanies');

Route::post('/companies/assign', 'CompanyController@assign');

Route::post('/contacts/import', 'ContactController@importContacts');

Route::get('/company/new', 'CompanyController@new');

Route::get('/contact', 'ContactController@show');

Route::get('/company/{company}', 'CompanyController@show');

Route::get('/companies/lookup', 'CompanyController@lookup');

Route::get('/companies/lookup/{term}', 'CompanyController@secondaryLookup');

Route::get('/quotes/{quote}', 'QuoteController@show');

Route::get('/quote/edit/{quote}', 'QuoteController@edit');

Route::get('/quote/for/{company}', 'QuoteController@create');

Route::get('/part', 'PartController@show');

Route::get('/parts/admin', 'PartController@partsAdmin');

Route::get('/activity', 'ActivityController@show');