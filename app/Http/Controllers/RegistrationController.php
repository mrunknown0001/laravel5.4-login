<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserLog;
use App\PasswordHistory;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmAccount;
use App\UserConfirm;


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


            // Save activation/confirmation code in database and send to user's email
            $u_confirm = new UserConfirm();
            $u_confirm->code = md5(uniqid($user->id_no, true));
            $u_confirm->id_no = $user->id_no;
            $u_confirm->expiration = time() + 1800;
            $u_confirm->save();

            Mail::to($user->email)->send(new ConfirmAccount($u_confirm->code, $u_confirm->id_no));

    		return view('pages.register_success');
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


    /*
     * Confirm and Activate user account
     */
    public function userActivityConfirm(Request $request)
    {
        $code = $request['c'];
        $id = $request['i'];

        $confirmation = UserConfirm::where(['code' => $code, 'id_no' => $id])->first();

        // Check if the Confirmation code is valid and not expired
        // if inValid
        if(empty($confirmation)) {
            return 'Invalid or Expired Activation/Confirmation Link!';
        }

        $user = User::whereIdNo($id)->first();
        // Check if the user is already confirmed/active
        if($user->active == 1) {
            return redirect()->route('login')->with('info','Your account is already confirmed and active. You can now login.');
        }

        // Activate user
        if(!empty($confirmation)) {
            $user->active = 1;
            if($user->save()) {
                return 'Activation Successfull!';
            }
        }

        return 'Error Occured! Please Try again.';
    }
}
