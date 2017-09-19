<?php namespace App\Http\Controllers\Acl;

use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller {

	/**
	 * Muestra el listado de usuarios.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user=User::get();
		return response()->json([
				"msg"=>"Success",
				"items"=>$user
			],200
		);
	}

	/**
	 * Crea un nuevo usuario.
	 *
	 * @return Response
	*/
	public function create(Request $request)
	{
		$data=$request->all();
		foreach ($data as $key => $value){
			$data[$key]=strtolower($value);
		}
        $validator = Validator::make($data, [
            'name' => 'required|between:4,30|alpha|unique:users',
            'lastname' => 'between:4,30|alpha',
            'phone' => 'numeric',
            'username' => 'required|between:3,10|alpha_dash|unique:users',
			'email' => 'required|email|between:5,200|unique:users',
			'password' => 'required|alpha_dash|min:8',
            'birthdate' => 'date',
        ]);
        if ($validator->fails() or $data["password"]!=$data["confirm"]) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
		}else{
			unset($data["confirm"]);
		}

		$user = new User();
		foreach ($data as $key => $value){
			if($key!="id" and $key!='token'){
				if($key=="password"){
					$user->$key = bcrypt($data[$key]);
				}else{
					$user->$key = $data[$key];
				}
			}
		}
		$user->save();

        return view('pawfinders::index');
		/*return response()->json([
				"msg"=>"success",
				"id"=>$user->id,
			],200
		);*/
	}

	/**
	 * Muestra un usuario especifico.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function read($id)
	{
		$data["id"]=$id;
        $validator = Validator::make($data, [
			'id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($user=User::find($id)){
			return response()->json([
					"msg"=>"success",
					"user"=>$user
				],200
			);
		}else{
			return response()->json([
					"msg"=>"error",
					"description"=>"No se ha encontrado el usuario"
				],200
			);
		}
	}

	/**
	 * Modifica un usuario especifico.
	 *
	 * @param  Request $request
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$data=$request->all();
		$data["id"]=$id;
		foreach ($data as $key => $value){
			$data[$key]=strtolower($value);
		}
        $validator = Validator::make($data, [
			'id' => 'integer',
            'name' => 'between:4,30|alpha|unique:users,name,'.$id,
            'username' => 'between:3,10|alpha_dash|unique:users,username,'.$id,
			'email' => 'email|between:5,200|unique:users,email,'.$id,
			'password' => 'min:8|alpha_dash',
			'state' => 'boolean',
			'new' => 'boolean',
			'ldap' => 'boolean',
        ]);
        if ($validator->fails()) {
		$validator->messages();
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		//var_dump($data);

		if ($user=User::find($id)){

			foreach ($data as $key => $value){
				if($key!="id" and $key!='token'){
					$user->$key = $data[$key];
				}
			}
			$user->save();
			return response()->json([
					"msg"=>"success",
					"user"=>$user
				],200
			);
		}else{
			return response()->json([
					"msg"=>"error",
					"description"=>"No se ha encontrado el usuario"
				],200
			);
		}
	}

	/**
	 * Elimina un usuario especifico
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$data["id"]=$id;
        $validator = Validator::make($data, [
			'id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($user=User::find($id)){
			$role=User::destroy($id);
			return response()->json([
					"msg"=>"Success"
				],200
			);
		}else{
			return response()->json([
					"msg"=>"error",
					"description"=>"No se ha encontrado el usuario"
				],200
			);
		}
	}

	/**
	 * Lista los roles de un determinado usuario.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function roles($id)
	{
		$data["id"]=$id;
        $validator = Validator::make($data, [
			'id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($role=User::find($id)){
			$role_user=RoleUser::leftjoin('roles','roles.id','=','role_users.role_id')
				->where('role_users.user_id',$id)
							->get(['name']);
			if ($role_user and current(current($role_user))){
				return response()->json([
						"msg"=>"success",
						"roles" => $role_user,
					],200
				);
			}else{
				$description = "Usuario sin roles";
			}
		}else{
			$description="No se ha encontrado el usuario";
		}
		return response()->json([
				"msg"=>"error",
				"description"=>$description,
			],200
		);
	}

	/**
	 * Lista los permisos de un determinado usuario.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function permissions($id)
	{
		$data["id"]=$id;
        $validator = Validator::make($data, [
			'id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($role=User::find($id)){
			$role_user=RoleUser::leftjoin('permission_roles','permission_roles.role_id','=','role_users.role_id')
				->leftjoin('permissions','permissions.id','=','permission_roles.permission_id')
				->where('role_users.user_id',$id)
							->get(['name']);
			if ($role_user and current(current($role_user))){
				return response()->json([
						"msg"=>"success",
						"permissions" => $role_user,
					],200
				);
			}else{
				$description = "Usuario sin permisos";
			}
		}else{
			$description="No se ha encontrado el usuario";
		}
		return response()->json([
				"msg"=>"error",
				"description"=>$description,
			],200
		);
	}
}
