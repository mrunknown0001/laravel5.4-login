<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\PasswordHistory;
use App\UserLog;
use App\PasswordReset;

class PasswordController extends Controller
{
    public function postResetPassword(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required'
    		]);

    	$email = $request['email'];

        $user = User::whereEmail($email)->first();

    	if(!empty($user)) {
            // Check if the user account is activated
            if($user->active != 1) {
                return 'Account is Inactive!';
            }

    		// Send Reset Link to Email Address
            // Create unique reset code for a user
            // code and user id no
            $code = $this->generateResetCode();
            $id = $user->id_no;
			Mail::to($email)->send(new ResetPassword($id, $code));

    		if(count(Mail::failures()) > 0) {

                // user log in requesting password reset

	    		return redirect()->route('password_reset')->with('error_msg', 'Error in sending reset link in your email. Please try again later.');
	    	}
	    	else {
                $pass_reset = new PasswordReset();
                $pass_reset->user_id_no = $id;
                $pass_reset->reset_code = $code;
                $pass_reset->expiration = time() + 1800;
                $pass_reset->save();

	    		return redirect()->route('password_reset')->with('success', 'Successfully sent reset link to your email address. Valid for 30 mins only!');
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
        $check = $this->checkOldPassword($password, $user->id_no);

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
    private function checkOldPassword($password, $id)
    {
        $passwords = PasswordHistory::whereUserIdNo($id)->get();

        foreach($passwords as $u) {
            if(password_verify($password, $u->password)) {
                return true;
            }
        }
        return false;
    }


    // Private method use to generate unique reset code
    private function generateResetCode()
    {
        $code = uniqid(time()) . str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');

        return $code;
    }


    // this method is use to redirect to reset link
    public function resetPasswordLink(Request $request)
    {
        $code = $request['c'];
        $id = $request['u'];

        $user = User::whereIdNo($id)->first();

        $check_reset = PasswordReset::where(['reset_code' => $code, 'user_id_no' => $id])
                        ->first();

        if(empty($check_reset)) {
            // Set view for invalid reset link
            return 'Invalid Reset Link';
        }

        if($check_reset->used == 1) {
            return 'Link Expired. (Used)';
        }

        if($check_reset->expiration >= time()) {
            // view for resetting password only once
            return view('pages.link_password_reset', ['id' => $user->id_no, 'code' => $code]);
        }

        return 'Link Expired!';
    }

    public function postPasswordReset(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:8|max:32|confirmed'
            ]);

        $password = $request['password'];
        $id = $request['id'];
        $code = $request['code'];

        // Check the combination of code and user id
        $check_reset = PasswordReset::where(['reset_code' => $code, 'user_id_no' => $id])
                        ->first();

        if(empty($check_reset)) {
            // Unauthorized modification occured
            // This is a sign of penetrationing the system
            return 'Hey! You Are trying to penetrate the system!';
        }

        // Check the user if present
        $user = User::whereIdNo($id)->first();

        if(empty($user)) {
            // There are modifaction on the form on client side
            // This is a sign of penetrating the system
            return 'Hey! You Are trying to penetrate the system!';
        }

        // check the password is already used in the past
        if($this->checkOldPassword($password, $user->id_no)) {
            // the password is already used in the past
            return 'Please use another password you haven\'t use before.';
        }

        // Mark the link as a used link
        $check_reset->used = 1;
        $check_reset->save();

        // Change the password here
        $user->password = bcrypt($password);
        
        if($user->save()) {
            // user log
            $log = new UserLog();
            $log->user_id_no = $user->id_no;
            $log->action = 'Reset Password on Link';
            $log->host = $request->ip();
            $log->os = $_SERVER['HTTP_USER_AGENT'];
            $log->browser = $_SERVER['HTTP_USER_AGENT'];
            $log->save();

            // password history
            $pass_history = new PasswordHistory();
            $pass_history->user_id_no = $user->id_no;
            $pass_history->password = $password;
            $pass_history->save();


            return redirect()->route('login')->with('success', 'Your Successfully Changed! You can now login with your new password');
        }
        
        return 'Error Occured! Please go back to reset link';

    }
}
