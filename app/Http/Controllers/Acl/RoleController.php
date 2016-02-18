<?php namespace App\Http\Controllers\Acl;

use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Permission_role;
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
				"msg"=>"Success",
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
		$role->name         = $data['name'];
		$role->display_name = $data['display_name']; // optional
		$role->description  = $data['description']; // optional
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
			$role->name		= $data['name'];
			$role->display_name	= $data['display_name'];
			$role->description	= $data['description'];
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
		if ($role=Role::destroy($id)){
			return response()->json([
					"msg"=>"Success"
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
	public function attach($role_id,$permission_id)
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
				$permission_role=Permission_role::where('role_id',$role_id)
												->where('permission_id',$permission_id)
												->first();
				if (!$permission_role){
					$role->attachPermission($permission);
					return response()->json([
							"msg"=>"Success"
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
	public function detach($role_id,$permission_id)
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
				if ($permission_role=Permission_role::where('role_id',$role_id)
												->where('permission_id',$permission_id)
												->delete()){
					return response()->json([
							"msg"=>"Success"
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
			$permission_role=Permission_role::leftjoin('permissions','permission_role.permission_id','=','permissions.id')
							->where('role_id',$id)
							->get();
			var_dumP($permission_role);
			if (!empty($permission_role)){
				return response()->json([
						"msg"=>"Success",
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

}
