<?php

namespace Modules\Pawfinders\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Paw;
use App\Models\User;

class PawfindersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function signup()
    {
        return view('pawfinders::signup');
    }
    public function signin()
    {
        return view('pawfinders::signin');
    }
    public function user($token)
    {
		$user=User::where('token',$token)
			->get(['token']);
		if(current(current($user))){
        	return view('pawfinders::signin');
		}else{
        	return view('pawfinders::signup');
		}
    }
    public function paw($token)
    {
		$user=User::where('token',$token)
			->get(['token']);
		if(current(current($user))){
        	return view('pawfinders::viewPaw');
		}else{
        	return view('pawfinders::noPaw');
		}
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
