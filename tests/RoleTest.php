<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRoles()
    {
		$this->json('GET','/acl/role?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
    }
    public function testCreateRole()
    {
		$data = array(
			'name'	=> 'tests',
			'display_name'	=> 'probador de contenidos',
			'description'	=> 'Tiene permiso de testear aplicaciones',
		);
		$this->json('POST','/acl/role?token='.$_ENV["tests"]["token"],$data)
			->seeJson();
		$rs = (array)json_decode($this->response->content());
		$_ENV["tests"]["current_role_id"]=$rs["id"];
		//var_dump($_ENV);
    }
    public function testGetRole()
    {
		$this->json('GET','/acl/role/'.$_ENV["tests"]["current_role_id"].'?token='.$_ENV["tests"]["token"])

			->seeJson(['msg'=>'success']);
    }
    public function testUpdateRole()
    {
		$data = array(
			'name'	=> 'rol de pruebas',
			'description'	=> 'mas pruebas',
		);
		$this->json('PUT','/acl/role/'.$_ENV["tests"]["current_role_id"].'?token='.$_ENV["tests"]["token"],$data)
			->seeJson();
		$this->response->content();
    }
    public function testDeleteRole()
    {
		$this->json('DELETE','/acl/role/'.$_ENV["tests"]["current_role_id"].'?token='.$_ENV["tests"]["token"]);
			//->seeJson();
		$this->response->content();
    }
    public function testGetUsers()
    {
		$this->json('GET','/acl/role/1/users?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testGetPermissions()
    {
		$this->json('GET','/acl/role/1/permissions?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testDetachPermission()
    {
		$this->json('DELETE','/acl/role/1/permission/1?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testAttachPermission()
    {
		$this->json('POST','/acl/role/1/permission/1?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testAttachUser()
    {
		$this->json('POST','/acl/role/1/user/2?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testDetachUser()
    {
		$this->json('DELETE','/acl/role/1/user/2?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
}
