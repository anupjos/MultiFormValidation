<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$contacts = Storage::disk('public')->get('contacts.json');
	$contacts = json_decode($contacts,true);
    return view('contacts', compact('contacts'));
});
Route::post('/ajax/save', function () {
    $input = Request::all();
    $errors = 0;
    foreach ($input['cForms'] as $key => $form) {
    	foreach ($form as $key => $value) {
    		if($key == "name" && ctype_alpha(str_replace(' ', '', $value)) === false){
                $errors = 1;
            }elseif($key == "phone" && !is_numeric($value)){
            	$errors = 1;
            }elseif($key == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)){
            	$errors = 1;
            }
    	} 
    }
	if($errors == 1){
        return $errors;
	}else{
		Storage::disk('public')->put('contacts.json', json_encode(($input['cForms'])));
        return $errors;
    }    
});

Route::post('/ajax/remove', function () {
    $input = Request::all();
    Storage::disk('public')->put('contacts.json', json_encode(($input['remForms'])));
});