<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
		$credentials = array(
			'username' => 'who',
			'password' => 'secret',
		);

		$this->json('POST','/auth/login', $credentials)
			->seeJson();

		$rs = (array)json_decode($this->response->content());
		if (array_key_exists('token',$rs)){
			$_ENV["tests"]["token"]=$rs["token"];
		}else{
			echo $rs["error"]."\n";;
			die;
		}
	}
}
