<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PermissionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetPermissions()
    {
		$this->json('GET','/acl/permission?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
    }
    public function testCreatePermission()
    {
		$data = array(
			'name'	=> 'tests',
			'display_name'	=> 'probador de contenidos',
			'description'	=> 'Tiene permiso de testear aplicaciones',
		);
		$this->json('POST','/acl/permission?token='.$_ENV["tests"]["token"],$data)
			->seeJson();
		$rs = (array)json_decode($this->response->content());
		$_ENV["tests"]["current_permission_id"]=$rs["id"];
		//var_dump($_ENV);
    }
    public function testGetPermission()
    {
		$this->json('GET','/acl/permission/'.$_ENV["tests"]["current_permission_id"].'?token='.$_ENV["tests"]["token"])

			->seeJson(['msg'=>'success']);
    }
    public function testUpdatePermission()
    {
		$data = array(
			'name'	=> 'prubasssss',
			'description'	=> 'mas pruebas',
		);
		$this->json('PUT','/acl/permission/'.$_ENV["tests"]["current_permission_id"].'?token='.$_ENV["tests"]["token"],$data)
			->seeJson();
		$this->response->content();
    }
    public function testDeletePermission()
    {
		$this->json('DELETE','/acl/permission/'.$_ENV["tests"]["current_permission_id"].'?token='.$_ENV["tests"]["token"])
			->seeJson();
		$this->response->content();
    }
    public function testGetRoles()
    {
		$this->json('GET','/acl/permission/1/roles?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
    public function testGetUsers()
    {
		$this->json('GET','/acl/permission/1/users?token='.$_ENV["tests"]["token"])
			->seeJson(['msg'=>'success']);
		$this->response->content();
    }
}
