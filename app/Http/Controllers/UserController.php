<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserLog()
    {
    	$logs = UserLog::whereUserIdNo(Auth::user()->id_no)
    			->select('user_id_no', 'action', 'host', 'os', 'browser', 'created_at')
    			->orderBy('created_at', 'DESC')->get();

    	// return view and embed the data for the user to view it
    	return $logs;
    }
}
