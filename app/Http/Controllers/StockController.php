<?php
namespace App\Http\Controllers;

use App\Stock;
use App\Broker;
use App\User;
use App\Invest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvestInserted;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //se usuario estiver registrado, pode visualizar, caso nao, redirecionado para tela de login
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $stocks = Stock::all();
        return view('stocks.index')->with('stocks', $stocks);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ////
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            return view('stocks.create');
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
        //valida antes de dar o store
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $this->validate(request(), [
                        'symbol' => 'required|string|max:9|unique:stocks,symbol',
                        'type' => 'required|string|max:255',
                    ], [
                        'symbol.required' => 'O código da ação deve ser inserido.',
                        'symbol.max' => 'O código da ação deve ter no máximo 9 caracteres.',
                        'symbol.unique' => 'O código da ação não deve ser duplicado.',
                        'type.required'  => 'O tipo de ação é requerido.',
                        'type.max'  => 'O tamanho máximo de texto é 255 caracteres.'
                    ]);
            $stock = new Stock;
            $stock->symbol = strtoupper($request->symbol);
            $stock->type = strtoupper($request->type);
            $stock->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'A ação foi inserida.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $stock = Stock::findOrFail($id);
        return $stock;
    }
    public function detail($id)
    {
        //
        $stock = Stock::findOrFail($id);
        return view('stocks.detail', array('stock' => $stock));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $stock = Stock::findOrFail($id);
        return view('stocks.edit', compact('stock', 'id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $stockUpdate = Stock::find($id);
            $this->validate(request(), [
                        'symbol' => ['required', 'string', 'max:9', Rule::unique('stocks')->ignore($stockUpdate->symbol, 'symbol'),],
                        'type' => ['required', 'string', 'max:255'],
                        ], [
                        'symbol.required' => 'O código da ação deve ser inserido.',
                        'symbol.max' => 'O código da ação deve ter no máximo 9 caracteres.',
                        'symbol.unique' => 'O código da ação não deve ser duplicado.',
                        'type.required'  => 'O tipo de ação é requerido.',
                        'type.max'  => 'O tamanho máximo de texto é 255 caracteres.'
                    ]);
            $stockUpdate->symbol = strtoupper($request->get('symbol'));
            $stockUpdate->type = strtoupper($request->get('type'));
            $stockUpdate->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'A ação foi atualizada.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $stockDel = Stock::findOrFail($id);
            $stockDel->delete();
            return response()->json([
            'message' => 'Ação deletada.|success'
                ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
    //deste ponto em diante sao metodos referentes ao armazenamento de investimentos em acoes, nao de acoes em si
    public function investstore(Request $request)
    {
        //insere o invest do tipo stock
        //transforma os valores para valores de banco de dados (virgulas e pontos)
        // dd($request->all());
        //$request = $this->formatcurrencytodb($request);
        $user = Auth::user();
        //$userid = $request->user()->id;
        //dd($request);
        //Validator::make($request->all(), [
        $this->validate(request(), [
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                        'quant' => 'required|numeric|min:1',
                        'price' => 'required|numeric|min:0.0001',
                        'broker_fee' => 'required|numeric|min:0',
                        'date_invest' => 'required|before:tomorrow',
                        'broker_name' => 'required|exists:brokers,name',
                    ], [
                        'symbol.required' => 'O código da ação deve ser inserido.',
                        'symbol.exists' => 'O código da ação deve constar no sistema.',
                        'quant.required'  => 'A quantidade é necessária.',
                        'price.required'  => 'O preço é necessário.',
                        'price.min'  => 'O preço deve ser maior que zero.',
                        'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
                        'date_invest.required'  => 'A data do investimento deve ser inserida.',
                        'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                        'broker_name.required'  => 'A corretora deve ser inserida.',
                        'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                    ]);

//           if ($validator->fails()) {
//             return redirect('invests/create')
//                         ->withErrors($validator)
//                         ->withInput();
//         };
        //dd($request);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $stockid = DB::table('stocks')->where('symbol', $request->symbol)->value('id');
        //dd($invests,$brokerid,$stockid,$user->id);
//         Invest::create([
//                         'type' => 'stock',
//                         'symbol' => strtoupper($invests['symbol']),
//                         'quant' => $invests['quant'],
//                         'price' => $invests['price'],
//                         'broker_fee' => $invests['brokerfee'],
//                         'date_invest' => new Carbon($invests['dateinvest']),
//                         'user_id' => $user->id,
//                         'stock_id' => $stockid,
//                         'broker_id' => $brokerid,
//             ]);
        //dd($request->dateinvest,$brokerid,$stockid,$user->id);
        $invest = new Invest;
        $invest->type = 'stock';
        $invest->symbol = strtoupper($request->symbol);
        $invest->quant = floatval($request->quant);
        $invest->price = $request->price;
        $invest->broker_fee = $request->broker_fee;
        $invest->date_invest = new Carbon($request->date_invest);
        $invest->user_id = $user->id;
        $invest->stock_id = $stockid;
        $invest->broker_id = $brokerid;
        //dd($invest);
        $invest->save();

        Mail::to($request->user())->send(new InvestInserted($invest));
        return response()->json([
                              'type' => 'success',
                              'message' => 'O investimento foi inserido.|success'
                          ]);
    }
    //metodo para transformar os valores da tela em valores de bd (virgulas e pontos) da corretagem e preco da acao
//     private function formatcurrencytodb($request)
//     {
//         //retira os pontos e substitui a virgula por pontos

//         $request['price'] = strtr($request['price'], array('.' => '', ',' => '.'));
//         $request['brokerfee'] = strtr($request['brokerfee'], array('.' => '', ',' => '.'));
//         //transforma em float
//         $request['price'] = floatval($request['price']);
//         $request['brokerfee'] = floatval($request['brokerfee']);
//         //retornar valor

//         //return $request;
//     }
    public function investedit($id)
    {
        //abre a tela onde vai ser feita a edicao
        $invest = invest::with('broker')->findOrFail($id);
        $invest->broker_name = $invest->broker->name;
        unset($invest->broker);
        //$invest->price = strtr($invest->price, array('.' => ','));
        $invest->price = floatval($invest->price);
        //$invest->broker_fee = strtr($invest->broker_fee, array('.' => ','));
        $invest->broker_fee = floatval($invest->broker_fee);
        $invest->quant = floatval($invest->quant);
        return view('stocks.stockinvestedit', compact('invest', 'id'));
        //return redirect('invests')->with('success', 'Foi ao lugar certo.');
    }
    public function investupdate(Request $request)
    {
        //primeiro ajusta os valores para formato de bando de dados, depois acha o investimento entao valida os dados e por fim faz o store
        //$request = $this->formatcurrencytodb($request);
        //acha o investimento
        $investUpdate = Invest::findOrFail($request->id);
        //pega o id do user
        $userid = $request->user()->id;
        //valida as informacoes
        $this->validate(request(), [
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                        'quant' => 'required|numeric|min:1',
                        'price' => 'required|numeric|min:0.0001',
                        'broker_fee' => 'required|numeric|min:0',
                        'date_invest' => 'required|before:tomorrow',
                        'broker_name' => 'required|exists:brokers,name',
                     ], [
                        'symbol.required' => 'O código da ação deve ser inserido.',
                        'symbol.exists' => 'O código da ação deve constar no sistema.',
                        'quant.required'  => 'A quantidade é necessária.',
                        'price.required'  => 'O preço é necessário.',
                        'price.min'  => 'O preço deve ser maior que zero.',
                        'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
                        'date_invest.required'  => 'A data do investimento deve ser inserida.',
                        'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                        'broker_name.required'  => 'A corretora deve ser inserida.',
                        'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                    ]);
        //pega info de broker e stock id
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $stockid = DB::table('stocks')->where('symbol', $request->symbol)->value('id');
        //atualiza BD
        $investUpdate->type = 'stock';
        $investUpdate->symbol = strtoupper($request->get('symbol'));
        $investUpdate->quant = $request->get('quant');
        $investUpdate->price = $request->get('price');
        $investUpdate->broker_fee = $request->get('broker_fee');
        $investUpdate->date_invest = new Carbon($request->get('date_invest'));
        $investUpdate->user_id = $userid;
        $investUpdate->stock_id = $stockid;
        $investUpdate->broker_id = $brokerid;
        $investUpdate->save();
        //retorna com sucesso
        return response()->json([
                              'type' => 'success',
                              'message' => 'O investimento foi atualizado.|success'
                          ]);
    }
    public function investdestroy($id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $investDel = invest::findOrFail($id);
            $investDel->delete();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O investimento foi deletado.|success'
                          ]);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
                                  'message' => 'Permissao invalida.|warning'
                                  ], 200);
        }
    }
}
