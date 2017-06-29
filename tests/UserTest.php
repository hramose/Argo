<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUsers()
    {
		$this->json('GET','/acl/user?token='.$_ENV["tests"]["token"])

			->seeJson(['msg'=>'Success']);
    }
    public function testCreateUser()
    {
		$data = array(
			'email' => 'tests@localhost.local',
			'name'	=> 'tests',
			'new'	=> true,
			'password'	=> 'secret8888',
			'state'	=> true,
			'username'	=> 'autotests',
			'ldap'	=> true,
		);
		$this->json('POST','/acl/user?token='.$_ENV["tests"]["token"],$data)
			->seeJson();
		$rs = (array)json_decode($this->response->content());
		$_ENV["tests"]["current_user_id"]=$rs["id"];
		//var_dump($_ENV);
    }
    public function testGetUser()
    {
		$this->json('GET','/acl/user/'.$_ENV["tests"]["current_user_id"].'?token='.$_ENV["tests"]["token"])

			->seeJson(['msg'=>'success']);
    }
    public function testUpdateUser()
    {
		$data = array(
			'new'	=> 'false',
			'username'	=> 'true',
		);
		$this->json('PUT','/acl/user/'.$_ENV["tests"]["current_user_id"].'?token='.$_ENV["tests"]["token"],$data)
			->seeJson();
		$this->response->content();
    }
    public function testDeleteUser()
    {
		$this->json('DELETE','/acl/user/'.$_ENV["tests"]["current_user_id"].'?token='.$_ENV["tests"]["token"])
			->seeJson();
		$this->response->content();
    }
    public function testGetRoles()
    {
		$this->json('GET','/acl/user/1/roles?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testGetPermissions()
    {
		$this->json('GET','/acl/user/1/permissions?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
}
