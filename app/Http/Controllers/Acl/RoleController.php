<?php namespace App\Http\Controllers\Acl;

use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Http\Controllers\Controller;

class RoleController extends Controller {

	/**
	 * Muestra el listado de roles.
	 *
	 * @return Response
	 */
	public function index()
	{
		$role=Role::get();
		return response()->json([
				"msg"=>"success",
				"items"=>$role
			],200
		);
	}

	/**
	 * Crea un nuevo rol.
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
            'name' => 'required|between:4,100|alpha_dash|unique:roles',
            'display_name' => 'between:0,100',
            'description' => 'between:0,255',
        ]);

        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }

		$role = new Role();
		foreach ($data as $key => $value){
			if($key!="id" and $key!='token'){
				$role->$key = $data[$key];
			}
		}
		$role->save();

		return response()->json([
				"msg"=>"success",
				"id"=>$role->id,
			],200
		);
	}

	/**
	 * Muestra un rol especifico
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
		if ($role=Role::find($id)){
			return response()->json([
					"msg"=>"success",
					"role"=>$role
				],200
			);
		}else{
			return response()->json([
					"msg"=>"Error",
					"description"=>"No se ha enccontrado el rol"
				],200
			);
		}
	}

	/**
	 * Modifica un rol especifico.
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
            'name' => 'between:4,100|alpha_dash|unique:roles,name,'.$id,
            'display_name' => 'between:0,100',
			'description' => 'email|between:0,255',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }

		if ($role=Role::find($id)){
			foreach ($data as $key => $value){
				if($key!="id" and $key!='token'){
					$role->$key = $data[$key];
				}
			}
			$role->save();
			return response()->json([
					"msg"=>"success",
					"role"=>$role
				],200
			);
		}else{
			return response()->json([
					"msg"=>"error",
					"description"=>"No se ha encontrado el rol"
				],200
			);
		}
	}

	/**
	 * Elimina un rol especifico
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
		if ($role=Role::find($id)){
			$role->delete();
			return response()->json([
					"msg"=>"success"
				],200
			);
		}else{
			return response()->json([
					"msg"=>"error",
					"description"=>"No se ha encontrado el rol"
				],200
			);
		}
	}

	/**
	 * Enlaza un permiso a un rol
	 *
	 * @param  int  $role_id
	 * @param  int  $permission_id
	 * @return Response
	 */
	public function attach_permission($role_id,$permission_id)
	{
		$data["permission_id"]=$permission_id;
		$data["role_id"]=$role_id;
        $validator = Validator::make($data, [
			'permission_id' => 'integer',
			'role_id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($role=Role::find($role_id)){
			if ($permission=Permission::find($permission_id)){
				$permission_role=PermissionRole::where('role_id',$role_id)
												->where('permission_id',$permission_id)
												->first();
				if (!$permission_role){
					$role->attachPermission($permission);
					return response()->json([
							"msg"=>"success"
						],200
					);
				}else{
					$description = "La relacion entre el rol y el permiso ya existe";
				}
			}else{
				$description="No se ha encontrado el permiso";
			}
		}else{
			$description="No se ha encontrado el rol";
		}
		return response()->json([
				"msg"=>"error",
				"description"=>$description,
			],200
		);
	}

	/**
	 * Rompe enlace entre un permiso y un rol
	 *
	 * @param  int  $role_id
	 * @param  int  $permission_id
	 * @return Response
	 */
	public function detach_permission($role_id,$permission_id)
	{
		$data["role_id"]=$role_id;
		$data["permission_id"]=$permission_id;
        $validator = Validator::make($data, [
			'role_id' => 'integer',
			'permission_id' => 'integer',
        ]);
        if ($validator->fails()) {
			return response()->json([
					"msg"=>"alert",
					"validator"=>$validator->messages(),
				],200
			);
        }
		if ($role=Role::find($role_id)){
			if ($permission=Permission::find($permission_id)){
				if ($permission_role=PermissionRole::where('role_id',$role_id)
												->where('permission_id',$permission_id)
												->delete()){
					return response()->json([
							"msg"=>"success"
						],200
					);
				}else{
					$description = "La relacion entre el rol y el permiso no existe";
				}
			}else{
				$description="No se ha encontrado el permiso";
			}
		}else{
			$description="No se ha encontrado el rol";
		}
		return response()->json([
				"msg"=>"error",
				"description"=>$description,
			],200
		);
	}

	/**
	 * Enlaza un usuario a un rol
	 *
	 * @param  int  $user_id
	 * @param  int  $role_id
	 * @return Response
	 */
	public function attach_user($role_id,$user_id)
	{
		$data["role_id"]=$role_id;
		$data["user_id"]=$user_id;
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
				$role_user=RoleUser::where('role_id',$role_id)
												->where('user_id',$user_id)
												->first();
				if (!$role_user){
					$user->attachRole($role);
					return response()->json([
							"msg"=>"success"
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
	public function detach_user($role_id,$user_id)
	{
		$data["role_id"]=$role_id;
		$data["user_id"]=$user_id;
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
				if ($role_user=RoleUser::where('role_id',$role_id)
												->where('user_id',$user_id)
												->delete()){
					return response()->json([
							"msg"=>"success"
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
	 * Rompe enlace entre un permiso y un rol
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
			$permission_role=PermissionRole::leftjoin('role_users','role_users.role_id','=','permission_roles.role_id')
							->leftjoin('permissions','permissions.id','=','permission_roles.permission_id')
							->where('permission_roles.role_id',$id)
							->get(['name']);
			if (!empty($permission_role)){
				return response()->json([
						"msg"=>"success",
						"users" => $permission_role,
					],200
				);
			}else{
				$description = "Rol vacio";
			}
		}else{
			$description="No se ha encontrado el rol";
		}
		return response()->json([
				"msg"=>"error",
				"description"=>$description,
			],200
		);
	}
	public function users($id)
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
			$role_user=RoleUser::leftjoin('users','users.id','=','role_users.user_id')
							->where('role_users.role_id',$id)
							->get(['name']);
			if (!empty($role_user)){
				return response()->json([
						"msg"=>"success",
						"users" => $role_user,
					],200
				);
			}else{
				$description = "Rol vacio";
			}
		}else{
			$description="No se ha encontrado el rol";
		}
		return response()->json([
				"msg"=>"error",
				"description"=>$description,
			],200
		);
	}

}
