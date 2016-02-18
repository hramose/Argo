<?php namespace App\Http\Controllers\Acl;

use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Role_user;
use App\Models\Permission;
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
            'username' => 'required|between:3,10|alpha_dash|unique:users',
			'email' => 'required|email|between:5,200|unique:users',
			'password' => 'required|alpha_dash|min:8',
			'state' => 'required|boolean',
			'new' => 'required|boolean',
			'ldap' => 'required|boolean',
        ]);

        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }

		$user = new User();
		$user->name		= $data['name'];
		$user->username	= $data['username'];
		$user->email	= $data['email'];
		$user->password	= bcrypt($data['password']);
		$user->state	= $data['state'];
		$user->new		= $data['new'];
		$user->ldap		= $data['ldap'];
		$user->save();

		return response()->json([
				"msg"=>"success",
				"id"=>$user->id,
			],200
		);
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
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }

		if ($user=User::find($id)){
			$user->name		= $data['name'];
			$user->username	= $data['username'];
			$user->email	= $data['email'];
			$user->password	= bcrypt($data['password']);
			$user->state	= $data['state'];
			$user->new		= $data['new'];
			$user->ldap		= $data['ldap'];
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
	 * Enlaza un usuario a un rol
	 *
	 * @param  int  $user_id
	 * @param  int  $role_id
	 * @return Response
	 */
	public function attach($user_id,$role_id)
	{
		$data["user_id"]=$user_id;
		$data["role_id"]=$role_id;
        $validator = Validator::make($data, [
			'role_id' => 'integer',
			'user_id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($user=User::find($user_id)){
			if ($role=Role::find($role_id)){
				$role_user=Role_user::where('role_id',$role_id)
												->where('user_id',$user_id)
												->first();
				if (!$role_user){
					$user->attachRole($role);
					return response()->json([
							"msg"=>"Success"
						],200
					);
				}else{
					$description = "La relacion entre el usuario y el rol ya existe";
				}
			}else{
				$description="No se ha encontrado el rol";
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
	 * Rompe enlace entre un usuario y un rol
	 *
	 * @param  int  $role_id
	 * @param  int  $user_id
	 * @return Response
	 */
	public function detach($user_id,$role_id)
	{
		$data["user_id"]=$user_id;
		$data["role_id"]=$role_id;
        $validator = Validator::make($data, [
			'user_id' => 'integer',
			'role_id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($user=User::find($user_id)){
			if ($role=Role::find($role_id)){
				if ($role_user=Role_user::where('role_id',$role_id)
												->where('user_id',$user_id)
												->delete()){
					return response()->json([
							"msg"=>"Success"
						],200
					);
				}else{
					$description = "La relacion entre el usuario y el rol no existe";
				}
			}else{
				$description="No se ha encontrado el rol";
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
			$role_user=Role_user::leftjoin('users','role_user.user_id','=','users.id')
							->leftjoin('roles','roles.id','=','role_user.user_id')
							->where('user_id',$id)
							->get(['roles.name','username','email']);
			
			if ($role_user and current(current($role_user))){
				return response()->json([
						"msg"=>"Success",
						"users" => $role_user,
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
		if ($role=Role::find($id)){
			$role_user=Role_user::leftjoin('users','role_user.user_id','=','users.id')
							->leftjoin('permission_role','permission_role.role_id','=','role_user.role_id')
							->leftjoin('permissions','permissions.id','=','permission_role.permission_id')
							->where('user_id',$id)
							->get(['name','username','email']);
			
			if ($role_user and current(current($role_user))){
				return response()->json([
						"msg"=>"Success",
						"users" => $role_user,
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
