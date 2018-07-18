<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Invest;
use App\monthlyQuote;
use App\DailyQuote;
use App\TreasuryQuote;

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

Route::post('/issuers/store', 'IssuerController@store');
Route::patch('/issuers/{id}', 'IssuerController@update');
Route::delete('/issuers/{id}/destroy', 'IssuerController@destroy');
Route::resource('issuers', 'IssuerController');

Route::post('/indices/store', 'IndexController@store');
Route::patch('/indices/{id}', 'IndexController@update');
Route::delete('/indices/{id}/destroy', 'IndexController@destroy');
Route::resource('indices', 'IndexController');

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
Route::post('/treasuries/investstore', 'TreasuryController@investstore'); //investimentos de titulos
Route::get('/treasuries/invests/{id}/edit', 'TreasuryController@investedit'); //editar investimento tipo treasuries
Route::patch('/treasuries/invests/{id}', 'TreasuryController@investupdate'); //atualizar investimento tipo treasuries
Route::delete('/treasuries/invests/{id}/destroy', 'TreasuryController@investdestroy'); //apagar investimento tipo treasuries
//titulos
Route::post('/treasuries/store', 'TreasuryController@store');
Route::patch('/treasuries/{id}', 'TreasuryController@update');
Route::delete('/treasuries/{id}/destroy', 'TreasuryController@destroy');
Route::resource('treasuries', 'TreasuryController');

//investimentos em renda fixa
Route::post('/securities/investstore', 'SecurityController@investstore'); //investimentos de renda fixa
Route::get('/securities/invests/{id}/edit', 'SecurityController@investedit'); //editar investimento tipo securities
Route::patch('/securities/invests/{id}', 'SecurityController@investupdate'); //atualizar investimento tipo securities
Route::delete('/securities/invests/{id}/destroy', 'SecurityController@investdestroy'); //apagar investimento tipo securities
//renda fixa
Route::post('/securities/store', 'SecurityController@store');//
Route::patch('/securities/{id}', 'SecurityController@update');
Route::delete('/securities/{id}/destroy', 'SecurityController@destroy');
Route::resource('securities', 'SecurityController');

//investimentos em fundos
Route::post('/funds/investstore', 'FundController@investstore'); //investimentos em fundos
Route::get('/funds/invests/{id}/edit', 'FundController@investedit'); //editar investimento tipo funds
Route::patch('/funds/invests/{id}', 'FundController@investupdate'); //atualizar investimento tipo funds
Route::delete('/funds/invests/{id}/destroy', 'FundController@investdestroy'); //apagar investimento tipo funds
//fundos
Route::post('/funds/store', 'FundController@store');//
Route::patch('/funds/{id}', 'FundController@update');
Route::delete('/funds/{id}/destroy', 'FundController@destroy');
Route::resource('funds', 'FundController');

Route::resource('monthlyquotes', 'MonthlyQuoteController');

Route::get('/dailyquotes/massinsert', 'DailyQuoteController@massinsert');
Route::post('/dailyquotes/store', 'DailyQuoteController@store');
Route::resource('dailyquotes', 'DailyQuoteController');

//treasury quotes
Route::resource('/treasuryquotes/scrp', 'TreasuryQuoteController@scrp');
Route::resource('treasuryquotes', 'TreasuryQuoteController');

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
//listagem de renda fixa
Route::get('/api/searchsecurities', function () {
    $query = Input::get('query');
    $securities = DB::table('securities')->where('name', 'like', $query.'%')->get();
    return response()->json($securities);
})->middleware('auth');
//listagem de fundos
Route::get('/api/searchfunds', function () {
    $query = Input::get('query');
    $funds = DB::table('funds')->where('name', 'like', $query.'%')->get();
    return response()->json($funds);
})->middleware('auth');
//listagem de corretoras
Route::get('/api/searchbrokers', function () {
    $query = Input::get('query');
    $brokers = DB::table('brokers')->where('name', 'like', $query.'%')->get();
    return response()->json($brokers);
})->middleware('auth');
//listagem de corretoras
Route::get('/api/searchissuers', function () {
    $query = Input::get('query');
    $issuers = DB::table('issuers')->where('name', 'like', $query.'%')->get();
    return response()->json($issuers);
})->middleware('auth');
//listagem de indices
Route::get('/api/searchindices', function () {
    $query = Input::get('query');
    $indices = DB::table('indices')->where('name', 'like', $query.'%')->get();
    return response()->json($indices);
})->middleware('auth');
Route::get('/api/brokers', function () {
    $brokers = DB::table('brokers')->get();
    return response()->json($brokers);
})->middleware('auth');
Route::get('/api/issuers', function () {
    $issuers = DB::table('issuers')->get();
    return response()->json($issuers);
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
Route::get('/api/index/issuers', function () {
    $issuers = DB::table('issuers')->get();
    return $issuers;
})->middleware('auth');
Route::get('/api/index/indices', function () {
    $indices = DB::table('indices')->get();
    return $indices;
})->middleware('auth');
Route::get('/api/index/stocks', function () {
    $stocks = DB::table('stocks')->get();
    return response()->json($stocks);
})->middleware('auth');
Route::get('/api/index/treasuries', function () {
    $treasuries = DB::table('treasuries')->get();
    return response()->json($treasuries);
})->middleware('auth');
Route::get('/api/index/securities', function () {
    $securities = DB::table('securities')->get();
    return response()->json($securities);
})->middleware('auth');
Route::get('/api/index/funds', function () {
    $funds = DB::table('funds')->get();
    return response()->json($funds);
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
        $invests = Invest::with([
        'dailyQuote' => function ($query) {
            $query->orderBy('timestamp', 'desc');
        },
        'treasuryQuote' => function ($query) {
            $query->where('type', '=', 'sell')->orderBy('timestamp', 'desc');
        },
        'treasury',
        'security',
        'issuer',
        'broker', 'user', 'stock'
         ])->get();
        // $invests = DB::table('index_invest_updated')->get();
    } else {
        $invests = Invest::with([
        'dailyQuote' => function ($query) {
            $query->orderBy('timestamp', 'desc');
        },
        'treasuryQuote' => function ($query) {
            $query->where('type', '=', 'sell')->orderBy('timestamp', 'desc');
        },
        'treasury',
        'security',
        'issuer',
        'broker', 'stock'])->where('user_id', $user->id)->get();
        // $invests = DB::table('index_invest_updated')->where('user_id', $user->id)->get();
    };
    foreach ($invests as $key => &$value) {

         $value->quote = 0;

        //tratamento para broker
        $value->broker_name = $value->broker->name;
        unset($value->broker);
        unset($value->broker_id);

        //tratamento para issuer
        if (isset($value->issuer)){
        $value->issuer_name = $value->issuer->name;
        }
        unset($value->issuer);
        unset($value->issuer_id);

        if (isset($value->stock)){
        //tratamento para stocks
        //para passar o nome do broker

        $value->inv = $value->stock->symbol;
        $value->symbol = $value->stock->symbol;
        $value->issuer_name = $value->stock->symbol;
        //retira o objeto do stock
        unset($value->stock);
        }

        if (isset($value->treasury)){
        //tratamento para treasury
        //para passar o nome do broker
        $value->inv = $value->treasury->code;
        $value->code = $value->treasury->code;
        $value->issuer_name = "Tesouro Nacional";
        //$value->rate = $value->rate / 10;
        //retira o objeto do treasury
        unset($value->treasury);
        }

        if (isset($value->security)){
        //tratamento para treasury
        //para passar o nome do broker
        $value->inv = $value->security->name . " + " . $value->rate . "%";
        $value->name = $value->security->name;
        //$value->rate = $value->rate / 10;
        //retira o objeto do treasury
        unset($value->security);
        }

        //retira o objeto de dentro do objeto, para renderizar corretamente
        //se existir, insere o valor da cotacao dos ultimo mes, caso não, vai zerado
        if (isset($value->dailyQuote[0]->close) && $value->type === 'stock') {
            $value->quote = $value->dailyQuote[0]->close;
//         } else {
//             $value->quote = 0;
        };
        //retira o objeto de dentro do objeto, para renderizar corretamente
        unset($value->dailyQuote);

      if (isset($value->treasuryQuote[0]->facevalue) && $value->type === 'treasury') {
            $value->quote = $value->treasuryQuote[0]->facevalue;
//         } else {
//             $value->quote = 0;
        };
        //retira o objeto de dentro do objeto, para renderizar corretamente
        unset($value->treasuryQuote);


        //retira informacoes que nao queremos renderizar
        unset($value->stock_id);


// foreach ($invests as $key => &$value) {
        if ($user->role_id !== 1) {
            unset($value->user_id);
            // unset($value->id);
        } else {
            $value->username = $value->user->name;
            unset($value->user);
        }
        //
        //tratamento para treasuries
        unset($value->treasury_id);
        unset($value->treasury);

        //tratamento para securities
        unset($value->security_id);
        unset($value->security);

        //tratamento para cores e %
        // $value->_cellVariants = 0;
        //
        // if($value->dif_percentage < 0) {
        //   $field = array('dif_percentage' => 'danger');
        //   $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
        // } elseif($value->dif_percentage > 0) {
        //   $field = array('dif_percentage' => 'success');
        //   $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
        // } else {
        //   $field = array('dif_percentage' => 'info');
        //   $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
        // }

      //
        if($value->dif_percentage !== 0) {
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
       } else {
          $value->percentage = 0;
          $field = array('percentage' => 'info');
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
// Route::get('/api/daily/getintchart', function () {
//     $query = Input::get('query');
//     $dailyQuotes = dailyQuote::with('stock')->where('stock_id', '=', $query)
//           ->whereDate('timestamp', '>', Carbon::now()->subMonth(1))->orderBy('timestamp', 'asc')->get();
//     foreach ($dailyQuotes as &$dailyQuote) {
//         //arruma o nome
//         $dailyQuote->stock_id = $dailyQuote->stock->symbol;
//         //depois retira o objeto de dentro do objeto
//         unset($dailyQuote->stock);
//         //ajusta as datas pro formato certo
//     }
//     return response()->json($dailyQuotes);
// })->middleware('auth');
//busca dados dos investimentos consolidados
Route::get('/api/consolidated', function () {
    $user = Auth::user();
    $consolidated = DB::table('index_invest_updated')->where('user_id', $user->id)->get();
    foreach ($consolidated as $consol){
    if ($user->role_id !== 1) {
      // unset($consol->name);
      // unset($consol->stock_id);
      unset($consol->user_id);
      unset($consol->id);
      unset($consol->date_updated);
      unset($consol->dif_reais);
    }
    }
    return response()->json($consolidated);
})->middleware('auth');
Route::get('/api/daily/getintchart/{table}/{id}', function ($table, $id) {
    //$id = Input::get('id');
    //$table = Input::get('table');
  if ($table === 'treasury') {
          $quotes = treasuryQuote::with('treasury')->where('treasury_id', '=', $id)
         ->where('type', '=', 'sell')
          ->whereDate('timestamp', '>', Carbon::now()->subMonth(1))->orderBy('timestamp', 'asc')->get();
    foreach ($quotes as &$quote) {
        //arruma o nome
        $quote->designation = $quote->treasury->code;
        //depois retira o objeto de dentro do objeto
        // unset($quote->treasury);
        //ajusta as datas pro formato certo
        //ajusta o preco
        $quote->price = $quote->facevalue;
    }
  } else if ($table === 'stock') {
        $quotes = dailyQuote::with('stock')->where('stock_id', '=', $id)
          ->whereDate('timestamp', '>', Carbon::now()->subMonth(1))->orderBy('timestamp', 'asc')->get();
    foreach ($quotes as &$quote) {
        //arruma o nome
        $quote->designation = $quote->stock->symbol;
        //depois retira o objeto de dentro do objeto
        // unset($quote->stock);
        //ajusta as datas pro formato certo
        //ajusta o preco
        $quote->price = $quote->close;
    }
  } else if ($table === 'security') {
    $quotes = 0;
  }
return response()->json($quotes);
})->middleware('auth');
//busca se há alguma
Route::get('/api/treasurycheck/{date}', function ($date) {
    //$user = Auth::user();
  if (isset($date)){
    $dataParsed = Carbon::parse($date);
    $checkForTreasuryData = DB::table('treasury_quotes')->where('timestamp', '<',  $date)->exists();
 return response()->json($checkForTreasuryData);
  }else {
    return response()->json(false);
  }
})->middleware('auth');
Route::get('/api/lastworkingdate', function () {
    //$user = Auth::user();
    $today = Carbon::today();
    $lastWorkingDate = DB::select('SELECT date FROM working_days WHERE working_day = 1 AND date < :date ORDER BY date DESC LIMIT 1', ['date' => $today]);
 return response()->json($lastWorkingDate);

})->middleware('auth');
