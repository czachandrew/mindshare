<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddressTest extends TestCase
{

	use DatabaseMigrations; 

	protected $user;
	protected $company;

	public function signIn($user)
    {
        $this->be($user);
    }
 
    public function setUp(){
        parent::setup();

        $this->user = factory('App\User')->create();
        $this->company = factory('App\Company')->create();
        $this->signIn($this->user);
   
    }
   
   /** @test **/ 
    public function can_create_address_through_company()
    {
        $company = factory('App\Company')->create();
        $company->addAddress(factory('App\Address')->make()->toArray());

        $this->assertTrue(true);
    }

    /** @test **/ 
    public function a_user_can_post_and_create_address()
    {
    	$company = factory('App\Company')->create();
    	$response = $this->actingAs($this->user)->post('/api/companies/' . $company->id . '/address/new', $address = factory('App\Address')->raw());
    	$this->assertEquals(200, $response->status());
    }

    /** @test **/ 
    public function quote_can_be_created_and_lines_added()
    {

    	$quote = factory('App\Quote')->create(['company_id' => $this->company->id, 'owner_id' => $this->user->id]);

    	$quote->addLineItem(factory('App\LineItem')->raw(['lineable_id' => $quote->id]));
    	$this->assertTrue(true);
    }
}
