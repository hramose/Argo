<?php namespace App\Http\Controllers\Acl;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;

class PermissionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$permission=Permission::get();
		return response()->json([
				"msg"=>"success",
				"items"=>$permission
			],200
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:4,100|unique:permissions',
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

		$permission = new Permission();
		$permission->name         = $request->name;
		$permission->display_name = $request->display_name; // optional
		$permission->description  = $request->description; // optional
		$permission->save();

		return response()->json([
				"msg"=>"success",
				"id"=>$permission->id,
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
		if ($permission=Permission::find($id)){
			return response()->json([
					"msg"=>"success",
					"permission"=>$permission
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
        $validator = Validator::make($request->all(), [
			'id' => 'integer',
            'name' => 'required|between:4,100|unique:permissions',
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
		//var_dump($data);

		if ($permission=Permission::find($id)){

			foreach ($data as $key => $value){
				if($key!="id" and $key!='token'){
					$permission->$key = $data[$key];
				}
			}
			$permission->save();
			return response()->json([
					"msg"=>"success",
					"permission"=>$permission
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
		if ($permission=Permission::find($id)){
			$permission=Permission::destroy($id);
			return response()->json([
					"msg"=>"success"
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
	 * Lista los permisos de un determinado usuario.
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
		if ($role=Role::find($id)){
			$permission_role=PermissionRole::leftjoin('roles','roles.id','=','permission_roles.role_id')
							->where('permission_roles.permission_id',$id)
							->get(['name']);
			
			if ($permission_role and current(current($permission_role))){
				return response()->json([
						"msg"=>"success",
						"users" => $permission_role,
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
	/**
	 * Lista los permisos de un determinado usuario.
	 *
	 * @param  int  $id
	 * @return Response
	 */
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
			$permission_role=PermissionRole::leftjoin('role_users','role_users.role_id','=','permission_roles.role_id')
							->leftjoin('users','users.id','=','role_users.user_id')
							->where('permission_roles.permission_id',$id)
							->get(['name']);
			
			if ($permission_role and current(current($permission_role))){
				return response()->json([
						"msg"=>"success",
						"users" => $permission_role,
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
