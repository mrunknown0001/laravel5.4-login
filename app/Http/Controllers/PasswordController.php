<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\PasswordHistory;
use App\UserLog;


class PasswordController extends Controller
{
    public function postResetPassword(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required'
    		]);

    	$email = $request['email'];

    	if(User::whereEmail($email)->first()) {
    		// Send Reset Link to Email Address

			Mail::to('madamt0001@gmail.com')->send(new ResetPassword);

    		if(count(Mail::failures()) > 0) {
	    		return redirect()->route('password_reset')->with('error_msg', 'Error in sending reset link in your email. Please try again later.');
	    	}
	    	else {
	    		return redirect()->route('password_reset')->with('success', 'Successfully sent reset link to your email address. Valid for 30mins only!');
	    	}
    	}

    	return redirect()->route('password_reset')->with('error_msg', 'Whoops! Email Address is not in our record!');
    }


    // Password Change/Update
    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:8|max:32',
            'old_password' => 'required',
            ]);

        $password = $request['password'];
        $old_password = $request['old_password'];

        $user = User::whereIdNo(Auth::user()->id_no)->first();

        // Check if password is used before
        $check = $this->checkOldPassword($password);

        if($check) {
            // return to the form and set a flash message
            return redirect()->route('change_password')->with('error_msg', 'Choose a password you haven\'t used before.');
        }

        // check old password
        $old_password_check = password_verify($old_password, $user->password);
        if($old_password_check) {
            // do the password change here and user logging
            $user->password = bcrypt($password);

            if($user->save()) {
                $pass_history = new PasswordHistory();
                $pass_history->user_id_no = $user->id_no;
                $pass_history->password = bcrypt($password);
                $pass_history->save();

                $log = new UserLog();
                $log->user_id_no = $user->id_no;
                $log->action = 'Password Change';
                $log->host = $request->ip();
                $log->os = $_SERVER['HTTP_USER_AGENT'];
                $log->browser = $_SERVER['HTTP_USER_AGENT'];
                $log->save();

                return redirect()->route('change_password')->with('success', 'Password Changed Successfully!');
            }
        }

        return redirect()->route('change_password')->with('error_msg', 'Old Password Incorrect!');

    }


    // Get Reset Password


    // Prevent sending another reset link in email by users within 30mins


    // private method for checking old password
    private function checkOldPassword($password)
    {
        $u = User::whereIdNo(Auth::user()->id_no)->first();

        if(password_verify($password, $u->password)) {
            return true;
        }
    }
}
