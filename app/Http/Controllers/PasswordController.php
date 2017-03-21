<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;

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


    // Get Reset Password


    // Prevent sending another reset link in email by users within 30mins


    // private method for checking old password
}
