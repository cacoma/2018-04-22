<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Invest;
use App\monthlyQuote;
use App\DailyQuote;

use Carbon\Carbon;

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

//investimentos em titulos
Route::post('/treasuries/investstore', 'TreasuryController@investstore'); //investimentos de acoes
Route::get('/treasuries/invests/{id}/edit', 'TreasuryController@investedit'); //editar investimento tipo stock
Route::patch('/treasuries/invests/{id}', 'TreasuryController@investupdate'); //atualizar investimento tipo stock
Route::delete('/treasuries/invests/{id}/destroy', 'TreasuryController@investdestroy'); //apagar investimento tipo stock
//titulos
Route::post('/treasuries/store', 'TreasuryController@store');
Route::patch('/treasuries/{id}', 'TreasuryController@update');
Route::delete('/treasuries/{id}/destroy', 'TreasuryController@destroy');
Route::resource('treasuries', 'TreasuryController');

Route::resource('monthlyquotes', 'MonthlyQuoteController');

Route::get('/dailyquotes/massinsert', 'DailyQuoteController@massinsert');
Route::post('/dailyquotes/store', 'DailyQuoteController@store');
Route::resource('dailyquotes', 'DailyQuoteController');

Route::view('/consolidated', 'invests.consolidated');
Route::resource('invests', 'InvestController');

//rota de teste de mailable

Route::get('/mailable', function () {
    $invest = App\invest::find(1);

    return new App\Mail\InvestInserted($invest);
});

//

//rotas que antes estavam em api
//listagem de stocks
Route::get('/api/searchstocks', function () {
    $query = Input::get('query');
    $stocks = DB::table('stocks')->where('symbol', 'like', $query.'%')->get();
    return response()->json($stocks);
})->middleware('auth');
//listagem de titulos do tesouro
Route::get('/api/searchtreasuries', function () {
    $query = Input::get('query');
    $treasuries = DB::table('treasuries')->where('code', 'like', $query.'%')->get();
    return response()->json($treasuries);
})->middleware('auth');
//listagem de corretoras
Route::get('/api/searchbrokers', function () {
    $query = Input::get('query');
    $brokers = DB::table('brokers')->where('name', 'like', $query.'%')->get();
    return response()->json($brokers);
})->middleware('auth');
Route::get('/api/brokers', function () {
    $brokers = DB::table('brokers')->get();
    return response()->json($brokers);
})->middleware('auth');
Route::get('/api/columns/{table}', function ($table) { //pega a coluna de certa tabela do BD
    $schema = DB::getSchemaBuilder()->getColumnListing($table);
    return response()->json($schema);
})->middleware('auth');
//verifica se codigo da acao existe no banco de dados
Route::get('/dailyquotes/api/stocks/symbol/{symbol}', function ($symbol) {
    if (DB::table('stocks')->where('symbol', $symbol)->exists()) {
        return '1';
    } else {
        return '0';
    }
})->middleware('auth');
Route::get('/api/index/brokers', function () {
    $brokers = DB::table('brokers')->get();
    return $brokers;
})->middleware('auth');
Route::get('/api/index/stocks', function () {
    $stocks = DB::table('stocks')->get();
    return response()->json($stocks);
})->middleware('auth');
Route::get('/api/index/treasuries', function () {
    $treasuries = DB::table('treasuries')->get();
    return response()->json($treasuries);
})->middleware('auth');
Route::get('/api/index/users', function () {
    $user = Auth::user();
    //dono e admin somente podem alterar
    if (Gate::allows('admin')) {
        $users = DB::table('users')->get();
        return response()->json($users);
    } else {
        return response()->json('flash', 'Acesso negado.|warning');
    }
})->middleware('auth');
Route::get('/api/index/invests', function () {
    $user = Auth::user();
    if ($user->role_id === 1) {
        $invests = Invest::with(['dailyQuote' => function ($query) {
            $query->orderBy('timestamp', 'desc');
        },
        'treasury',
        'broker', 'user', 'stock'])->get();
    } else {
        $invests = Invest::with(['dailyQuote' => function ($query) {
            $query->orderBy('timestamp', 'desc');
        },
        'treasury',
        'broker', 'stock'])->where('user_id', $user->id)->get();
    };
    foreach ($invests as $key => &$value) {

        //tratamento para broker
        $value->broker_name = $value->broker->name;
        unset($value->broker);
        unset($value->broker_id);

        if (isset($value->symbol)){
        //tratamento para stocks
        //para passar o nome do broker
        $value->symbol = $value->stock->symbol;
        //retira o objeto do stock
        unset($value->stock);
        }
        //retira o objeto de dentro do objeto, para renderizar corretamente
        //se existir, insere o valor da cotacao dos ultimo mes, caso não, vai zerado
        if (isset($value->dailyQuote[0]->close)) {
            $value->quote = $value->dailyQuote[0]->close;
        } else {
            $value->quote = 0;
        };
        //retira o objeto de dentro do objeto, para renderizar corretamente
        unset($value->dailyQuote);
        //retira informacoes que nao queremos renderizar
        unset($value->stock_id);
        if ($user->role_id !== 1) {
            unset($value->user_id);
            unset($value->id);
        } else {
            $value->username = $user->name;
            unset($value->user);
        }

        //tratamento para treasuries
        unset($value->treasury_id);
        unset($value->treasury);

        //tratamento para cores e %
        $value->percentage = ($value->quote / $value->price - 1);
        if ($value->price >= $value->quote) {
            $field = array('percentage' => 'danger');
            $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
        } elseif ($value->price == $value->quote) {
            $field = array('percentage' => 'info');
            $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
        } else {
            $field = array('percentage' => 'success');
            $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
        }
    }
    return response()->json($invests);
})->middleware('auth');
Route::get('/api/monthly/getintchart', function () {
    $query = Input::get('query');
    $monthlyQuotes = monthlyQuote::with('stock')->where('stock_id', '=', $query)
          ->whereDate('timestamp', '>', Carbon::now()->subMonth(12))->orderBy('timestamp', 'asc')->get();
    foreach ($monthlyQuotes as &$monthlyQuote) {
        //arruma o nome
        $monthlyQuote->stock_id = $monthlyQuote->stock->symbol;
        //depois retira o objeto de dentro do objeto
        unset($monthlyQuote->stock);
        //ajusta as datas pro formato certo
    }
    return response()->json($monthlyQuotes);
})->middleware('auth');
Route::get('/api/daily/getintchart', function () {
    $query = Input::get('query');
    $dailyQuotes = dailyQuote::with('stock')->where('stock_id', '=', $query)
          ->whereDate('timestamp', '>', Carbon::now()->subMonth(1))->orderBy('timestamp', 'asc')->get();
    foreach ($dailyQuotes as &$dailyQuote) {
        //arruma o nome
        $dailyQuote->stock_id = $dailyQuote->stock->symbol;
        //depois retira o objeto de dentro do objeto
        unset($dailyQuote->stock);
        //ajusta as datas pro formato certo
    }
    return response()->json($dailyQuotes);
})->middleware('auth');
//busca dados dos investimentos consolidados
Route::get('/api/consolidated', function () {
    $user = Auth::user();
    $consolidated = DB::table('consolidated')->where('user_id', $user->id)->get();
    return response()->json($consolidated);
})->middleware('auth');
