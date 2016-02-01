<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	public function __construct()
	{
		// Aplica el middleware jwt.auth 
		// a todos los métodos en este controlador
		// excepto al método index.
	    $this->middleware('jwt.auth', ['except' => ['index']]);
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('welcome');
	}

}
