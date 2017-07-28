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
				'name' => 'admon', 
				'username' => 'who', 
				'email' => 'who@whooami.me', 
				'password' => Hash::make('secret'), 
				'identification_number'=>'10101010',
				'birthdate'=>'1996-01-01'
			],
			[
				'name' => 'jefe',
				'username' => 'boss',
				'email' => 'cejebuto@gmail.com',
				'password' => Hash::make('secret'),
				'identification_number'=>'20202020',
				'birthdate'=>'1989-01-01'
			],
			[
				'name' => 'pruebas',
				'username' => 'tests',
				'email' => 'whotezts@gmail.com',
				'password' => Hash::make('secret'),
				'identification_number'=>'30303030',
				'birthdate'=>'1979-01-01'
			],
        );
		foreach($users as $user){
			\DB::table('users')->insert(array(
					'name'=>$user['name'],
					'username'=>$user['username'],
					'email'=>$user['email'],
					'password'=>$user['password'],
					'password'=>$user['password'],
					'identification_number'=>$user['identification_number'],
					'birthdate'=>$user['birthdate'],
					'created_at'=>date("Y-m-y H:i:s"),
					'updated_at'=>date("Y-m-y H:i:s"),
				)
			);
		}
        //
    }
}
