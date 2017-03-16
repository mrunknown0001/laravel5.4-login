<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserLog;

class LoginController extends Controller
{
    /*
     * User Login Method
     */
    public function postUserLogin(Request $request)
    {
    	$this->validate($request, [
    		'username' => 'required',
    		'password' => 'required'
    		]);

    	$username = $request['username'];
    	$password = $request['password'];
    	$remember = $request['remember'];


		if (Auth::attempt(['username' => $username, 'password' => $password, 'active' => 1], $remember)) {
			// Authentication passed...

			// UserLog goes from this line
            $log = new UserLog();
            $log->user_id_no = Auth::user()->id_no;
            $log->action = 'Login';
            $log->host = $request->ip();
            $log->os = $_SERVER['HTTP_USER_AGENT'];
            $log->browser = $_SERVER['HTTP_USER_AGENT'];

            $log->save();

			return redirect()->intended('dashboard');
		}

		return redirect()->route('login')->with('error_msg', 'Username or Password is incorrect!');
    }


    /*
     * Users Logout Method
     */
    public function logout()
    {
        if(!Auth::check()) {
            return redirect()->route('login')->with('error_msg', 'You are already logged out!');
        }

        $id_no = Auth::user()->id_no;

        Auth::logout();

        // UserLog goes here
        $log = new UserLog();
        $log->user_id_no = $id_no;
        $log->action = 'Logout';
        $log->host = $request->ip();
        $log->os = $_SERVER['HTTP_USER_AGENT'];
        $log->browser = $_SERVER['HTTP_USER_AGENT'];

        return redirect()->route('login')->with('success', 'You have been logged out!');
    }
}
