<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserLog;
use App\PasswordHistory;

class RegistrationController extends Controller
{
    
    /*
     * This method is use to regsiter a new user in the 
     * system.
     */
    public function postRegisterUser(Request $request)
    {
    	// Validating user input on server side
    	$this->validate($request, [
    		'username' => 'required|unique:users|max:32',
    		'first_name' => 'required|max:100',
    		'last_name' => 'required|max:100',
    		'birthdate' => 'required',
    		'gender' => 'required',
    		'email' => 'required|email|unique:users',
    		'mobile_number' => 'required|max:11',
    		'password' => 'required|min:8|max:32|confirmed',
    		]);

    	// Assign request data to variables
    	$username = strtolower($request['username']);
    	$id_no = $this->generateIdNoNumber();
    	$firstname = ucwords($request['first_name']);
    	$lastname = ucwords($request['last_name']);
    	$birthdate = date('Y-m-d', strtotime($request['birthdate']));
    	$gender = $request['gender'];
    	$email = strtolower($request['email']);
    	$mobile = $request['mobile_number'];
    	$password = bcrypt($request['password']);

    	// Inserting Data in respected fields in database
    	$user = new User();
    	$user->username = $username;
    	$user->id_no = $id_no;
    	$user->first_name = $firstname;
    	$user->last_name = $lastname;
    	$user->birthdate = $birthdate;
    	$user->gender = $gender;
    	$user->email = $email;
    	$user->mobile = $mobile;
    	$user->password = $password;

    	if($user->save()) {
            // Save the password in password histories
            $pass_history = new PasswordHistory();
            $pass_history->user_id_no = $user->id_no;
            $pass_history->password = $password;
            $pass_history->save();

    		// This part is the user log section
            $log = new UserLog();
            $log->user_id_no = $user->id_no;
            $log->action = 'Account Creation/Registration';
            $log->host = $request->ip();
            $log->os = $_SERVER['HTTP_USER_AGENT'];
            $log->browser = $_SERVER['HTTP_USER_AGENT'];

            $log->save();

    		return redirect()->route('login')->with('success', 'Successfully Created Account! You Can Now Login!');
    	}

    	return redirect()->route('register')->with('error_msg', 'Registration Failed! Please Try Again Later.');

    }

    /*
     * This Private Function generate
     * unique user identification
     */
    private function generateIdNoNumber() {
	    $number = mt_rand(10000000, 99999999);

	    if ($this->idNoExists($number)) {
	        return generateBarcodeNumber();
	    }
	    return $number;
	}

	/*
	 * This Private method checks the generated
	 * user identification if already exist
	 */
	private function idNoExists($number) {
	    return User::whereIdNo($number)->exists();
	}
}
