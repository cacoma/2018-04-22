<?php

namespace App\Http\Controllers;

use App\DailyQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DailyQuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dailyQuotes = dailyQuote::with('stock')->get();
        //coloca o nome dos symbols em variavel propria
        foreach ($dailyQuotes as &$dailyQuote) {
            $dailyQuote->stock_id = $dailyQuote->stock->symbol;
            //depois retira o objeto de dentro do objeto
            unset($dailyQuote->stock);
        }
        return view('dailyQuotes.index')->with('dailyQuotes', $dailyQuotes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            return view('dailyQuotes.create');
        } else {
            return back()->with('message', 'Permissao invalida.|warning');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $quote = $this->validate(request(), [
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                    ], [
                        'symbol.required' => 'O código da ação deve ser inserido.',
                        'symbol.exists' => 'O código da ação deve constar no sistema.',
                    ]);
            $symbol =  strtoupper($quote['symbol']);
            //testar se stock esta cadastrada no banco
            $stockid = DB::table('stocks')->where('symbol', $symbol)->value('id');
            //primeiro testa se tem algum registro, se nao houver insere todos os dados
            if (DB::table('daily_quotes')->where('stock_id', $stockid)->doesntExist()) {
                $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                //testa se o alpha vantage esta funcionando
                if ($handle !== false	&& is_resource($handle)) {
                    fgetcsv($handle);
//                     //esse comando exclui a segunda linha do csv, pois trata-se do dia de hoje, referente ao fechamento do mes atual. Os dados só consolidaram no final do mes.
//                     fgetcsv($handle);
                    //while para cadastrar todos os dados
                    while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                        $dailyQuotes = new DailyQuote();
                        $dailyQuotes->stock_id = $stockid;
                        $dailyQuotes->timestamp = $data[0];
                        $dailyQuotes->open = $data[1];
                        $dailyQuotes->high = $data[2];
                        $dailyQuotes->low = $data[3];
                        $dailyQuotes->close = $data[4];
                        $dailyQuotes->volume = $data[5];
                        $dailyQuotes->save();
                    }
                    fclose($handle);
                } else {
                    //nao funcionou a importacao,problema no AV
                    return response()->json([
                              'message' => 'Algo errado com o sitio AV.|warning'
                          ]);
                    // return redirect('home')->with('flash', 'Algo errado com o sitio AV.|warning');
                }
                //retorna que deu tudo certo
                return response()->json([
                              'message' => 'Dados inseridos no BD.|success'
                          ]);
            // return redirect('home')->with('flash', 'Dados inseridos no BD.|success');
                        //caso exista mais de um registro, buscar se o possui registro do mes passado, para inserir somente este
            } elseif (DB::table('daily_quotes')->where('stock_id', $stockid)->whereDate('timestamp', '>', Carbon::now()->subMinutes(55))->doesntExist()) {
                $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                if ($handle !== false	&& is_resource($handle)) {
                    //esse comando exclui a primeira linha do csv
                    fgetcsv($handle);
//                 //esse comando exclui a segunda linha do csv, pois trata-se do dia de hoje, referente ao fechamento do mes atual. Os dados só consolidaram no final do mes.
//                 fgetcsv($handle);
                    //esse insere o fechamento do ultimo mes já concluido
                    $data = fgetcsv($handle, 1000, ',');
                    if ($data[0] == Carbon::today()) {
                        $dailyQuotes = new DailyQuote();
                        $dailyQuotes->stock_id = $stockid;
                        $dailyQuotes->timestamp = $data[0];
                        $dailyQuotes->open = $data[1];
                        $dailyQuotes->high = $data[2];
                        $dailyQuotes->low = $data[3];
                        $dailyQuotes->close = $data[4];
                        $dailyQuotes->volume = $data[5];
                        $dailyQuotes->save();
                        fclose($handle);
                        Log::info("Execução da importação unitario: " . $symbol);
                        return response()->json([
                              'message' => 'Dados inseridos no BD.|success'
                          ]);
                    } else {
                        fclose($handle);
                        return response()->json([
                              'message' => 'Data mais atual da fonte não é de hoje.|warning'
                          ]);
                    }
                } else {
                    //nao funcionou a importacao,problema no AV
                    return response()->json([
                              'message' => 'Algo errado com o sitio AV.|warning'
                          ]);
                    // return redirect('home')->with('flash', 'Algo errado com o sitio AV.|warning');
                }

                //  return redirect('home')->with('flash', 'Dados inseridos no BD.|success');
            } else {
                //neste caso ja existe registro do ultimo mes
                return response()->json([
                              'message' => 'Dados de hoje já existentes na base.|warning'
                          ]);
                //return redirect('home')->with('flash', 'Dados já existentes na base.|warning');
            }
        } else {
            return response()->json([
                              'message' => 'Permissao invalida.|warning'
                          ]);
            //redirect('home')->with('flash', 'Permissao invalida.|warning');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DailyQuote  $dailyQuote
     * @return \Illuminate\Http\Response
     */
    public function show(DailyQuote $dailyQuote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DailyQuote  $dailyQuote
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyQuote $dailyQuote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DailyQuote  $dailyQuote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyQuote $dailyQuote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DailyQuote  $dailyQuote
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyQuote $dailyQuote)
    {
        //
    }

    /*
    mass assignment prototype
    */

    public function massinsert(DailyQuote $dailyQuote)
    {
        //
        $user = Auth::user();
        $log = new \stdClass;
        $log->message = ["Os resultados foram: |warning"];
        $log->success = [];
        $log->fail = [];
        $log->upToDate = [];
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $stockids = DB::table('stocks')->select('id', 'symbol')->get();

            foreach ($stockids as &$stockid) {
                //primeiro testa se tem algum registro, se nao houver insere todos os dados
                if (DB::table('daily_quotes')->where('stock_id', $stockid->id)->doesntExist()) {
                    $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $stockid->symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                    //testa se o alpha vantage esta funcionando
                    if ($handle !== false	&& is_resource($handle)) {
                        fgetcsv($handle);
//                     //esse comando exclui a segunda linha do csv, pois trata-se do dia de hoje, referente ao fechamento do mes atual. Os dados só consolidaram no final do mes.
//                     fgetcsv($handle);
                        //pega a primeira data para ver se bate com hoje, pois o alphavantage pode nao ter a informacao de hoje
                        $data = fgetcsv($handle, 1000, ',');

                        if ($data[0] == Carbon::today()) {
                            $dailyQuotes = new DailyQuote();
                            $dailyQuotes->stock_id = $stockid->id;
                            $dailyQuotes->timestamp = $data[0];
                            $dailyQuotes->open = $data[1];
                            $dailyQuotes->high = $data[2];
                            $dailyQuotes->low = $data[3];
                            $dailyQuotes->close = $data[4];
                            $dailyQuotes->volume = $data[5];
                            $dailyQuotes->save();
                            //fclose($handle);

                            //while para cadastrar todos os dados
                            while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                                $dailyQuotes = new DailyQuote();
                                $dailyQuotes->stock_id = $stockid->id;
                                $dailyQuotes->timestamp = $data[0];
                                $dailyQuotes->open = $data[1];
                                $dailyQuotes->high = $data[2];
                                $dailyQuotes->low = $data[3];
                                $dailyQuotes->close = $data[4];
                                $dailyQuotes->volume = $data[5];
                                $dailyQuotes->save();
                            }
                            fclose($handle);
                            array_push($log->success, $stockid->symbol);
                        } else {
                            fclose($handle);
                            array_push($log->fail, $stockid->symbol . " : data a inserir não é a mais atual.");
                        }
                    } else {
                        //nao funcionou a importacao,problema no AV
                        array_push($log->fail, $stockid->symbol . !" : não foi possível acesso com o AV.");
//                   return response()->json([
//                               'message' => 'Algo errado com o sitio AV.|warning'
//                           ]);
                 // return redirect('home')->with('flash', 'Algo errado com o sitio AV.|warning');
                    }
                    //retorna que deu tudo certo
                   // array_push($log->success, $stockid->symbol);
//                 return response()->json([
//                               'message' => 'Dados inseridos no BD.|success'
//                           ]);
               // return redirect('home')->with('flash', 'Dados inseridos no BD.|success');
                        //caso exista mais de um registro, buscar se o possui registro do mes passado, para inserir somente este
                } elseif (DB::table('daily_quotes')->where('stock_id', $stockid->id)->whereDate('timestamp', '>', Carbon::now()->subMinutes(55))->doesntExist()) {
                    $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $stockid->symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                    if ($handle !== false	&& is_resource($handle)) {
                        //esse comando exclui a primeira linha do csv
                        fgetcsv($handle);
//                 //esse comando exclui a segunda linha do csv, pois trata-se do dia de hoje, referente ao fechamento do mes atual. Os dados só consolidaram no final do mes.
//                 fgetcsv($handle);
                        //esse insere o fechamento do ultimo mes já concluido
                        $data = fgetcsv($handle, 1000, ',');
                        if ($data[0] == Carbon::today()) {
                            $dailyQuotes = new DailyQuote();
                            $dailyQuotes->stock_id = $stockid->id;
                            $dailyQuotes->timestamp = $data[0];
                            $dailyQuotes->open = $data[1];
                            $dailyQuotes->high = $data[2];
                            $dailyQuotes->low = $data[3];
                            $dailyQuotes->close = $data[4];
                            $dailyQuotes->volume = $data[5];
                            $dailyQuotes->save();
                            fclose($handle);
                            array_push($log->success, $stockid->symbol);
                        } else {
                            fclose($handle);
                            array_push($log->fail, $stockid->symbol . " : data a inserir não é a mais atual.");
                        }
                    } else {
                        //nao funcionou a importacao,problema no AV
                        array_push($log->fail, $stockid->symbol . " : não foi possível acesso ao AV.");
//                  return response()->json([
//                               'message' => 'Algo errado com o sitio AV.|warning'
//                           ]);
             // return redirect('home')->with('flash', 'Algo errado com o sitio AV.|warning');
                    }
                    // array_push($log->success, $stockid->symbol);
//               return response()->json([
//                               'message' => 'Dados inseridos no BD.|success'
//                           ]);
            //  return redirect('home')->with('flash', 'Dados inseridos no BD.|success');
                } else {
                    //neste caso ja existe registro do ultimo mes
                    array_push($log->upToDate, $stockid->symbol);
//               return response()->json([
//                               'message' => 'Dados já existentes na base.|warning'
//                           ]);
             //return redirect('home')->with('flash', 'Dados já existentes na base.|warning');
                }
                //atrasamos a proxima execucao em 2 segundos pois o site do alphavantage recomenda uma request por segundo
                sleep(1);
            }
            Log::info("Execução da importação em massa : " . json_encode($log));
            return response()->json([
                    'message' => $log
                   ]);
        } else {
            return back()->with('message', 'Permissao invalida.|warning');
        }
    }
}
