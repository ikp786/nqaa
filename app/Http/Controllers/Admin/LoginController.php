<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }


    public function login(Request $request)
	{
		$request->validate([
			'email' => 'required',
			'password' => 'required',
		]);
		Auth::logout();
		$credentials = $request->only('email', 'password');
		$remember_me = $request->has('remember_me') ? true : false;
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'Admin'], $remember_me)) {
			// if (Auth::user()->role_id == 1) {
			//     return redirect('/superadmin/dashboard');
			// }else{
			//     Auth::logout();
			//     return redirect('/superadmin/login')->with('error','You Are Not Allowed!');
			// }
			return redirect('/admin/dashboard');
		}
		return redirect()->back()->with('Failed', 'Invalid username or password.');
	}

}
