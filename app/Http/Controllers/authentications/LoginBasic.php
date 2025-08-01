<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Input;


class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }




  public function doLogin(Request $request)
       
    {
  
         
        // $credentials = $request->validate([
        //     'username' => ['required', 'email-username'],
        //     'password' => ['required'],
        // ]);

        // validate the info, create rules for the inputs
		$rules = array(
			'email'  => 'required', // make sure the email is an actual email
			'password'  => 'required|min:60' // password can only be alphanumeric and has to be greater than 3 characters
		);


   

     	$credentials = array(
				'email' 	=> Input::get('email-username'),
				'password' 	=> Input::get('password')
			);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

           return redirect()->intended('/dashboard');
        }
        return redirect()->back()->withError('The provided credentials do not match our records!');
    }



  public function doLogout()
  {
    	Auth::logout();
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

}
