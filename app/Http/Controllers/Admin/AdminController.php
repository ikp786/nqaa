<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login( Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {            
                return redirect()->route('admin.dashboard');
        }else{
            return redirect()->back()->With('Failed', 'Invalid login details')->withInput($request->only(['email']));
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/admin/login');
    }
}
