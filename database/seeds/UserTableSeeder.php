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
				'name' => 'Administrador', 
				'username' => 'who', 
				'email' => 'who@pawfinders.me', 
				'password' => Hash::make('secret'), 
				'birthdate'=>'1996-05-23',
				'token'=>'whooami',
			],
			[
				'name' => 'Cristian Guasca', 
				'username' => 'cristian', 
				'email' => 'cristian@pawfinders.co', 
				'password' => bcrypt('secret'), 
				'birthdate'=>'1990-04-14',
				'token'=>'cristian',
			],
			[
				'name' => 'Alejandro barahona', 
				'username' => 'alejo', 
				'email' => 'alejo@pawfinders.co', 
				'password' => bcrypt('secret'), 
				'birthdate'=>'1989-03-15',
				'token'=>'-alejo-',
			],
        );
		foreach($users as $user){
			\DB::table('users')->insert(array(
					'name'=>$user['name'],
					'username'=>$user['username'],
					'email'=>$user['email'],
					'password'=>$user['password'],
					'birthdate'=>$user['birthdate'],
					'token'=>$user['token'],
					'created_at'=>date("Y-m-y H:i:s"),
					'updated_at'=>date("Y-m-y H:i:s"),
				)
			);
		}
        //
    }
}
