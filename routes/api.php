<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api'); // <= config/auth.php/api


Route::get('/test', function(){
   return response()->json([
        'user' => [
           'id' => '6',
           'firstName' => 'tommy',
           'lastName' => 'mawmaw'
        ]
   	]);
});

//Route::group(['middleware' => ['api', 'isVerified']], function (){
Route::resource('users', 'UserController'); // register 
//});
Route::post('login', 'APILoginController@login');
Route::get('verify/{token}', 'APIRegisterController@verify'); 
Route::resource('products', 'ProductsController');

// only authenticated users can access products data
Route::group(['middleware' => 'auth:api'], function(){
  Route::resource('shipping-info', 'AddressController');
  Route::post('checkout', 'CheckoutController@storePayment');
});

