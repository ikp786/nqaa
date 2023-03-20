<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


class AdminAuthController extends Controller
{
	//
	
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
			
			return redirect('/admin/dashboard');
		}elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'Merchant'], $remember_me)) {
			
			return redirect()->route('admin.orders.index');
		}
		return redirect()->back()->with('Failed', 'Invalid username or password.');
	}

	public function changePasswordGet()
	{
		$title         = "Change Password";
		$data          = compact('title');
		return view('admin.settings.change-password', $data);
	}

	public function changePassword(Request $request)
	{
		$error_message =    [
			'current_password.required'    => 'Current Password should be required',
			'new_password.required'        => 'New Password should be required',
			'new_password.regex'   		   => 'Provide password in valid format',
			'min'                  		   => 'Password should be minimum :min character'
		];
		$validatedData = $request->validate([
			'current_password'             => ['required', new MatchOldPassword],
			'new_password'                 => 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
		], $error_message);
		try {
			$user_details = auth()->user();
			if ($user_details) {
				\DB::beginTransaction();
				$change = User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
				\DB::commit();
				return redirect()->route('admin.dashboard.index')->With('Success', 'Password change succssfully');
			} else {
				return redirect()->back()->With('Failed', 'UNAUTHORIZE ACCESS');
			}
		} catch (\Throwable $e) {
			\DB::rollback();
			return redirect()->back()->With('Failed', $e->getMessage() . ' on line ' . $e->getLine());
		}
	}

	public function forgot_password_view()
	{
		return view('admin.forget-password');
	}
	public function forgot_password(Request $request)
	{
		// $response =  Http::get('https://nbfc-server.herokuapp.com/api/ledger/getLedgers');
		// dd($response->json());
		$error_message = 	[
			'email.required'  	=> 'Email address should be required',
			'email.email'  	    => 'Email address Not a valid format',
			'email.exists'  	=> 'Email address not found.',
		];
		$rules = [
			'email'       => 'required|email|exists:users,email',
		];
		$this->validate($request, $rules, $error_message);
		try {
			$user_detail = User::where('email', $request->email)->first();
			if (!isset($user_detail)) {
				return redirect()->back()->With('Failed', 'Email Address not found');
			}

			$verifaction_otp = rand(111111, 999999);
			$verifaction_otp = 2222;
			$email_data = ['user_name' => $user_detail->first_name, 'verifaction_otp' => $verifaction_otp];
			// \Mail::to($user_detail->email)->send(new \App\Mail\ForgotPassword($email_data));
			Session::put('forget_password_otp', $verifaction_otp);
			Session::put('forget_password_email', $user_detail->email);
			return redirect()->route('admin.reset_password')->With('Success', 'OTP sent successfully. Please check your email and verify');
		} catch (\Throwable $e) {

			return redirect()->back()->With("Failed", $e->getMessage() . ' on line ' . $e->getLine());
		}
	}

	public function reset_password_view()
	{
		return view('admin.reset-password');
	}

	public function reset_password(Request $request)
	{
		$error_message = 	[
			'new_password.required'     => 'New Password should be required',
			'new_password.regex'        => 'Provide password in valid format',
			'min'                       => 'Password should be minimum :min character',
			'otp.required'			    => 'Otp should be required',
		];
		$rules = [
			'new_password'              => 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
			'otp'						=> 'required',
		];
		$this->validate($request, $rules, $error_message);
		try {
			$otp   =   Session::get('forget_password_otp');
			$email =   Session::get('forget_password_email');
			if ($otp != $request->otp) {
				return redirect()->back()->With('Failed', 'OTP not match please try a valid OTP');
			}
			// check email
			if (!User::where(['email' => $email, 'role_id' => 1])->first()) {
				return redirect()->back()->With('Failed', 'WE COULD NOT FOUND ANY ACCOUNT');
			}
			$user_detail = User::where('email', $email)->first();

			\DB::beginTransaction();
			$user_detail->password = Hash::make($request->new_password);
			$user_detail->save();
			\DB::commit();
			return redirect()->route('admin.login')->With('Success', 'Password Update successfully');
		} catch (\Throwable $e) {
			\DB::rollback();
			return redirect()->back()->With("Failed", $e->getMessage() . ' on line ' . $e->getLine());
		}
	}
	public function logout()
	{
		Session::flush();
		Auth::logout();
		return redirect()->route('admin.login');
	}
}
