<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index'); 

Route::post('auth/login', 'Auth\AuthController@authenticate');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['jwt.auth']], function () {
	//Lista de control de acceso.
	Route::group(['namespace' => 'Acl', 'prefix' => 'acl', 'middleware' => ['role:admon']], function () {
		//Usuarios.
		Route::get('user', 'UserController@index'); 
		Route::post('user', 'UserController@create'); 
		Route::get('user/{id}', 'UserController@read'); 
		Route::put('user/{id}', 'UserController@update'); 
		//Roles.
		Route::get('role', 'RoleController@index'); 
		Route::post('role', 'RoleController@create'); 
		Route::get('role/{id}', 'RoleController@read'); 
		Route::put('role/{id}', 'RoleController@update'); 
		Route::delete('role/{id}', 'RoleController@delete'); 
		//Permisos.
		Route::get('permission', 'PermissionController@index'); 
		Route::post('permission', 'PermissionController@create'); 
		Route::get('permission/{id}', 'PermissionController@read'); 
		Route::put('permission/{id}', 'PermissionController@update'); 
		Route::delete('permission/{id}', 'PermissionController@delete'); 
		//Relaciones roles con permisos.
		Route::post('role/{role_id}/{permission_id}', 'RoleController@attach'); 
		Route::delete('role/{role_id}/{permission_id}', 'RoleController@detach'); 
		//Relaciones usuarios con roles.
		Route::post('user/{user_id}/{role_id}', 'UserController@attach'); 
		Route::delete('user/{user_id}/{role_id}', 'UserController@detach'); 
		//Listado de relaciones.
		#Roles.
		Route::get('role/{role_id}/users', 'RoleController@users');//Usuarios que pertenecen a un determinado rol.
		Route::get('role/{role_id}/permissions', 'RoleController@permissions');//Permisos asociados a un rol.
		#Usuarios.
		Route::get('user/{role_id}/roles', 'UserController@roles');//Roles a los que pertenece un usuario.
		Route::get('user/{role_id}/permissions', 'UserController@permissions');//Permisos que tiene un usuario. 
		#Permisos.
		Route::get('permission/{role_id}/roles', 'PermissionController@roles');//Roles que tienen un determinado permiso.
		Route::get('permission/{role_id}/users', 'PermissionController@users');//Usuarios que tienen un determinado permiso.
	});
});
