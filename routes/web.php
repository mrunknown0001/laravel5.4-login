<?php

Route::get('/', function () {
    return view('home');
});


Route::group(['middleware' => 'auth'], function () {

	Route::get('dashboard', function () {
		return view('pages.dashboard');
	});

});


Route::get('login', function () {
	if(Auth::check()) {
		return Redirect::to('dashboard');
	}

	return view('login');
})->name('login');

Route::post('login', [
	'uses' => 'LoginController@postUserLogin',
	'as' => 'post_user_login'
	]);


Route::get('logout', [
	'uses' => 'LoginController@logout',
	'as' => 'logout'
	]);


Route::get('register', function() {
	if(Auth::check()) {
		return Redirect::to('dashboard');
	}

	return view('register');
})->name('register');


Route::post('register', [
	'uses' => 'RegistrationController@postRegisterUser',
	'as' => 'post_register_user'
	]);
