<?php

namespace Modules\Pawfinders\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PawfindersDatabaseSeeder extends Seeder
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
				'name' => 'Enérgico', 
			],
			[
				'name' => 'Tranquilo', 
			],
			[
				'name' => 'Curioso', 
			],
			[
				'name' => 'Bravo', 
			],
			[
				'name' => 'Nervioso', 
			],
			[
				'name' => 'Amistoso', 
			],
			[
				'name' => 'Perezoso', 
			],
        );
		foreach($users as $user){
			\DB::table('paw_natures')->insert(array(
					'name'=>$user['name'],
					'created_at'=>date("Y-m-y H:i:s"),
					'updated_at'=>date("Y-m-y H:i:s"),
				)
			);
		}
    }
}
