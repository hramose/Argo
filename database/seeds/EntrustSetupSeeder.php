<?php

use Illuminate\Database\Seeder;

class EntrustSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		\DB::table('roles')->insert(array(
				'name'=>"admon",
				'display_name'=>"administrador",
				'description'=>"rol para los administradores del sistema",
				'created_at'=>date("Y-m-y H:i:s"),
				'updated_at'=>date("Y-m-y H:i:s"),
			)
		);

		\DB::table('permissions')->insert(array(
				'name'=>"acl",
				'display_name'=>"roles y perfiles",
				'description'=>"administracion de la creación de usuarios y las listas de control de acceso",
				'created_at'=>date("Y-m-y H:i:s"),
				'updated_at'=>date("Y-m-y H:i:s"),
			)
		);

		\DB::table('permission_role')->insert(array(
				'permission_id'=>1,
				'role_id'=>1,
			)
		);

		\DB::table('role_user')->insert(array(
				'user_id'=>1,
				'role_id'=>1,
			)
		);
    }
}
