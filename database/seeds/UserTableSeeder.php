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
			[
				'name' => 'administrador', 
				'username' => 'admon', 
				'email' => 'admon@whooami.me', 
				'password' => Hash::make('secret'), 
				'birthdate'=>'1990-01-01'
			],
        );
		foreach($users as $user){
			\DB::table('users')->insert(array(
					'name'=>$user['name'],
					'username'=>$user['username'],
					'email'=>$user['email'],
					'password'=>$user['password'],
					'password'=>$user['password'],
					'birthdate'=>$user['birthdate'],
					'created_at'=>date("Y-m-y H:i:s"),
					'updated_at'=>date("Y-m-y H:i:s"),
				)
			);
		}
        //
    }
}
