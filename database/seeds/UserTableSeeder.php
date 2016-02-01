<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
                ['name' => 'admon', 'username' => 'who', 'email' => 'who@whooami.me', 'password' => Hash::make('secret')],
                ['name' => 'jefe', 'username' => 'boss', 'email' => 'cejebuto@gmail.com', 'password' => Hash::make('secret')],
                ['name' => 'pruebas', 'username' => 'tests', 'email' => 'whotezts@gmail.com', 'password' => Hash::make('secret')],
        );
		foreach($users as $user){
			\DB::table('users')->insert(array(
					'name'=>$user['name'],
					'username'=>$user['username'],
					'email'=>$user['email'],
					'password'=>$user['password'],
					'created_at'=>date("Y-m-y H:i:s"),
					'updated_at'=>date("Y-m-y H:i:s"),
				)
			);
		}
        //
    }
}
