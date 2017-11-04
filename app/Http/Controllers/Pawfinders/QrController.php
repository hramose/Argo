<?php

namespace Modules\Pawfinders\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pawfinders\Models\Paw;
use App\Models\User;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function createPaw()
    {
		do{
		#https://stackoverflow.com/questions/4356289/php-random-string-generator
		$length = 8;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$randomString;
		//QrCode::format('png')->size(100)->generate('Transfórmame en un QrCode!');
		//echo "new qr";
		//die;
			$paw=Paw::where('token',$randomString)
				->get(['token']);
		}while(current(current($paw)));
		$url="http://localhost:8000/pawfinders/paw/$randomString";
        return view('pawfinders::qr')->with('url',$url);;
    }
    public function createUser()
    {
		do{
			#https://stackoverflow.com/questions/4356289/php-random-string-generator
			$length = 8;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			$randomString;
			//QrCode::format('png')->size(100)->generate('Transfórmame en un QrCode!');
			//echo "new qr";
			//die;
			$user=User::where('token',$randomString)
				->get(['token']);
		}while(current(current($user)));
		$url="http://localhost:8000/pawfinders/user/$randomString";
		return view('pawfinders::qr')->with('url',$url);;
    }
    public function index()
    {
        return view('pawfinders::QrIndex');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('pawfinders::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('pawfinders::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('pawfinders::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
