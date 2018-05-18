<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users/profile', 'UserController@profileshow');
Route::patch('/users/{id}', 'UserController@update');
Route::delete('/users/{id}/destroy', 'UserController@destroy');
Route::resource('users', 'UserController');

Route::post('/brokers/store', 'BrokerController@store');
Route::patch('/brokers/{id}', 'BrokerController@update');
Route::delete('/brokers/{id}/destroy', 'BrokerController@destroy');
Route::resource('brokers', 'BrokerController');
//investimentos em acoes
Route::post('/stocks/investstore', 'StockController@investstore'); //investimentos de acoes
Route::get('/stocks/invests/{id}/edit', 'StockController@investedit'); //editar investimento tipo stock
Route::patch('/stocks/invests/{id}', 'StockController@investupdate'); //atualizar investimento tipo stock
Route::delete('/stocks/invests/{id}/destroy', 'StockController@investdestroy'); //apagar investimento tipo stock
//acoes
Route::post('/stocks/store', 'StockController@store');//
Route::patch('/stocks/{id}', 'StockController@update');
Route::delete('/stocks/{id}/destroy', 'StockController@destroy');
Route::resource('stocks', 'StockController');

Route::resource('monthlyquotes', 'MonthlyQuoteController');

Route::get('/dailyquotes/massinsert', 'DailyQuoteController@massinsert');
Route::post('/dailyquotes/store', 'DailyQuoteController@store');
Route::resource('dailyquotes', 'DailyQuoteController');

Route::resource('invests', 'InvestController');

//rota de teste de mailable

Route::get('/mailable', function () {
    $invest = App\invest::find(1);

    return new App\Mail\InvestInserted($invest);
});

//

//rotas que antes estavam em api
//listagem de stocks
Route::get('/api/searchstocks',function(){
 $query = Input::get('query');
 $stocks = DB::table('stocks')->where('symbol','like',$query.'%')->get();
 return response()->json($stocks);
})->middleware('auth');
//listagem de corretoras
Route::get('/api/searchbrokers',function(){
 $query = Input::get('query');
 $brokers = DB::table('brokers')->where('name','like',$query.'%')->get();
 return response()->json($brokers);
})->middleware('auth');
Route::get('/api/brokers',function(){
 $brokers = DB::table('brokers')->get();
 return response()->json($brokers);
})->middleware('auth');
Route::get('/api/columns/{table}',function($table){ //pega a coluna de certa tabela do BD
 $schema = DB::getSchemaBuilder()->getColumnListing($table);
 //$schema = DB::table('columns')->where('table_schema', 'cacoma')->where('table_name',$table)->get();
 return response()->json($schema);
})->middleware('auth');
//verifica se codigo da acao existe no banco de dados
Route::get('/dailyquotes/api/stocks/symbol/{symbol}', function($symbol){
  if (DB::table('stocks')->where('symbol', $symbol)->exists()) {
    return '1';
  } else {
    return '0';
  }
})->middleware('auth');
