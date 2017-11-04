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

Route::post('auth/signup', 'Acl\UserController@create');
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
		Route::delete('user/{id}', 'UserController@delete'); 
		Route::get('user/{id}/roles', 'UserController@roles');#Lista los roles de un usuario.
		Route::get('user/{id}/permissions', 'UserController@permissions');#Lista los permisos de un usuario. 
		//Permisos.
		Route::get('permission', 'PermissionController@index'); 
		Route::post('permission', 'PermissionController@create'); 
		Route::get('permission/{id}', 'PermissionController@read'); 
		Route::put('permission/{id}', 'PermissionController@update'); 
		Route::delete('permission/{id}', 'PermissionController@delete'); 
		Route::get('permission/{id}/roles', 'PermissionController@roles');#Lista los roles de un permiso.
		Route::get('permission/{id}/users', 'PermissionController@users');#Lista los usuarios de un permiso.
		//Roles.
		Route::get('role', 'RoleController@index'); 
		Route::post('role', 'RoleController@create'); 
		Route::get('role/{id}', 'RoleController@read'); 
		Route::put('role/{id}', 'RoleController@update'); 
		Route::delete('role/{id}', 'RoleController@delete'); 
		Route::get('role/{id}/users', 'RoleController@users');#Lista los usuarios de un rol.
		Route::get('role/{id}/permissions', 'RoleController@permissions');#Lista los permisos de un rol.
		#PermissionRole
		Route::post('role/{role_id}/permission/{permission_id}', 'RoleController@attach_permission');#Enlaza un permiso a un rol.
		Route::delete('role/{role_id}/permission/{permission_id}', 'RoleController@detach_permission');#Rompe enlace entre un permiso y un rol.
		#RoleUser
		Route::post('role/{role_id}/user/{user_id}', 'RoleController@attach_user');#Enlaza un usuario a un rol.
		Route::delete('role/{role_id}/user/{user_id}', 'RoleController@detach_user');#Rompe enlace entre un usuario y un rol.
		#Pawfinders
		Route::get('paw', 'PawController@index'); 
		Route::post('paw', 'PawController@create'); 
		Route::get('paw/{id}', 'PawController@read'); 
		Route::put('paw/{id}', 'PawController@update'); 
		Route::delete('paw/{id}', 'PawController@delete'); 
	});
});
