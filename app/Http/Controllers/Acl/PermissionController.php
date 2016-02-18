<?php namespace App\Http\Controllers\Acl;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

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
				"msg"=>"Success",
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
	}
}
