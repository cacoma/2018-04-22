<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//listagem de stocks
Route::get('/searchstocks',function(){
 $query = Input::get('query');
 $stocks = DB::table('stocks')->where('symbol','like',$query.'%')->get();
 return response()->json($stocks);
});
//listagem de corretoras
Route::get('/searchbrokers',function(){
 $query = Input::get('query');
 $brokers = DB::table('brokers')->where('name','like',$query.'%')->get();
 return response()->json($brokers);
});
