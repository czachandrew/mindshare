<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ExampleTest extends TestCase
{
    
    use DatabaseMigrations;

    protected $company;
    protected $user; 
    protected $task;

    public function signIn($user)
    {
        $this->be($user);
    }
 
    public function setUp(){
        parent::setup();

        $this->user = factory('App\User')->create();
        $this->signIn($this->user);
        $this->company = factory('App\Company')->create();
        $this->task = factory('App\Task')->create(['owner_id' => $this->user->id]);
    }
    /** @test **/
    public function a_user_can_view_companies()
    {
        $this->get('/home')->assertSee($this->company->name);
    }
    /** @test **/
    public function a_user_can_view_tasks(){
        $this->get('/home')->assertSee($this->task->title);
    }

    public function a_user_can_add_notes_to_task(){
        $this->task->addNote(['content' => 'Here is a note body']);
    }
}
