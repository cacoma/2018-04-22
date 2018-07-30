<?php

namespace App\Http\Controllers;

use App\Operation;
use Illuminate\Http\Request;
use App\Stock;
use App\Broker;
use App\User;
use App\Invest;
use Carbon\Carbon;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;

class OperationController extends Controller
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function show(Operation $operation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function edit(Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Operation  $operation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operation $operation)
    {
        //
    }

    public function storeStock(Request $request, Operation $operation)
    {
        //
        $user = Auth::user();
        $quant = $request->quant;
        $this->validate(request(), [
                        'signal' => [
                                        'required',
                                        Rule::in(['buy', 'sell']),
                                    ],
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                        'quant' => 'required|numeric|min:1',
                        'price' => 'required|numeric|min:0.0001',
                        'broker_fee' => 'required|numeric|min:0',
                        'date_invest' => 'required|date|before_or_equal:tomorrow',
                        'broker_name' => 'required|exists:brokers,name',
                        'sellDate_invest'=> 'required|date|before_or_equal:tomorrow|after:date_invest',
                        'sellPrice' => 'required|numeric|min:0.0001',
                        'sellQuant' => 'required|numeric|min:1|max:' . $quant,
                        'sellBroker_fee' => 'required|numeric|min:0',
                    ], [
                        'signal.required' => 'Favor informar se é compra ou venda.',
                        'symbol.required' => 'O código da ação deve ser inserido.',
                        'symbol.exists' => 'O código da ação deve constar no sistema.',
                        'date_invest.required' => 'A data de investimento deve ser inserida.',
                        'date_invest.before_or_equal' => 'A data de investimento deve ser de hoje ou anterior.',
                        'quant.required'  => 'A quantidade é necessária.',
                        'price.required'  => 'O preço é necessário.',
                        'price.min'  => 'O preço deve ser maior que zero.',
                        'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
                        'date_invest.required'  => 'A data do investimento deve ser inserida.',
                        'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                        'broker_name.required'  => 'A corretora deve ser inserida.',
                        'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                        'sellDate_invest.required' => 'A data de venda deve ser inserida.',
                        'sellDate_invest.after' => 'A data de venda deve ser depois da compra.',
                        'sellDate_invest.before_or_equal' => 'A data de venda deve ser de hoje ou anterior.',
                        'sellPrice.required'  => 'O preço de venda é necessário.',
                        'sellPrice.min'  => 'O preço de venda deve ser maior que zero.',
                        'sellQuant.required'  => 'A quantidade de venda é necessária.',
                        'sellQuant.min'  => 'A quantidade de venda mínima é 0.',
                        'sellQuant.max'  => 'A quantidade de venda não deve ser maior que a quantidade disponível.',
                        'sellBroker_fee.min'  => 'A corretagem de venda deve ser inserida, mesmo que zero.',
                    ]);

        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $stockid = DB::table('stocks')->where('symbol', $request->symbol)->value('id');

        $operation = new Operation;
        $operation->type = 'stock';
        $operation->inv_id = $request->id;
        $operation->quant = $request->sellQuant;
        $operation->quant_orig = $request->quant_orig;
        $operation->price = $request->sellPrice;
        $operation->price_orig = $request->price;
        $operation->broker_fee = $request->sellBroker_fee;
        $operation->broker_fee_orig = $request->broker_fee;
        $operation->date_operation = new Carbon($request->sellDate_invest);
        $operation->date_invest = new Carbon($request->date_invest);
        $operation->user_id = $user->id;
        $operation->stock_id = $stockid;
        $operation->broker_id = $brokerid;
        $operation->save();

        //atualiza o quant no investimento original
        $investUpdate = Invest::findOrFail($request->id);

        debug($request->id);
        debug($investUpdate);
        \Debugbar::info('funcionando');
        debug($request->sellQuant);
        debug($investUpdate->quant);

        if (floatval($request->sellQuant) === floatval($investUpdate->quant)) {
            debug('entrou ===');
            // $investUpdate->quant = $investUpdate->quant - $request->sellQuant;
            $investUpdate->liquidated = 1;
        }
        $investUpdate->quant = $investUpdate->quant - $request->sellQuant;
        $investUpdate->save();
        return response()->json([
                              'type' => 'success',
                              'message' => 'O investimento foi atualizado.|success'
                          ]);
    }
    public function storeTreasury(Request $request, Operation $operation)
    {
        $user = Auth::user();
        $quant = $request->quant;
        $this->validate(request(), [
                      'signal' => [
                                      'required',
                                      Rule::in(['buy', 'sell']),
                                  ],
                      'name' => 'required|string|max:255|exists:treasuries,name',
                      'quant' => 'required|numeric|min:0.0001',
                      'price' => 'required|numeric|min:0.0001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
                      'rate' => 'required',
                      'broker_name' => 'required|exists:brokers,name',
                      'sellDate_invest'=> 'required|date|before_or_equal:tomorrow|after:date_invest',
                      'sellPrice' => 'required|numeric|min:0.0001',
                      // 'sellRate' => 'required|numeric|min:0',
                      'sellQuant' => 'required|numeric|min:0.0001|max:' . $quant,
                      'sellBroker_fee' => 'required|numeric|min:0',
                  ], [
                      'signal.required' => 'Favor informar se é compra ou venda.',
                      'name.required' => 'O código do título deve ser inserido.',
                      'name.exists' => 'O código do título deve constar no sistema.',
                      'quant.required'  => 'A quantidade é necessária.',
                      'price.required'  => 'O preço é necessário.',
                      'price.min'  => 'O preço deve ser maior que zero.',
                      'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
                      'broker_fee.required'  => 'A corretagem deve ser inserida, mesmo que zero.',
                      'rate.required'  => 'A taxa deve ser inserida, mesmo que zero.',
                      'date_invest.required'  => 'A data do investimento deve ser inserida.',
                      'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                      'broker_name.required'  => 'A corretora deve ser inserida.',
                      'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                      'sellDate_invest.required' => 'A data de venda deve ser inserida.',
                      'sellDate_invest.after' => 'A data de venda deve ser depois da compra.',
                      'sellDate_invest.before_or_equal' => 'A data de venda deve ser de hoje ou anterior.',
                      'sellPrice.required'  => 'O preço de venda é necessário.',
                      'sellPrice.min'  => 'O preço de venda deve ser maior que zero.',
                      'sellQuant.required'  => 'A quantidade de venda é necessária.',
                      'sellQuant.min'  => 'A quantidade de venda mínima é 0.',
                      'sellQuant.max'  => 'A quantidade de venda não deve ser maior que a quantidade disponível.',
//                       'sellRate.required'  => 'A taxa de venda é necessária.',
//                       'sellRate.min'  => 'A taxa de venda mínima é 0.',
//                       'sellRate.max'  => 'A taxa de venda não deve ser maior que a quantidade disponível.',
                      'sellBroker_fee.min'  => 'A corretagem de venda deve ser inserida, mesmo que zero.',
                  ]);
        // if ($request->signal === 'sell') {
        //     $request->quant = 0 - $request->quant;
        // };
        //dd($request);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $treasuryid = DB::table('treasuries')->where('name', $request->name)->value('id');

        $operation = new Operation;
        $operation->type = 'treasury';
        $operation->inv_id = $request->id;
        $operation->quant = $request->sellQuant;
        $operation->quant_orig = $request->quant_orig;
        $operation->price = $request->sellPrice;
        $operation->price_orig = $request->price;
        $operation->broker_fee = $request->sellBroker_fee;
        $operation->broker_fee_orig = $request->broker_fee;
        $operation->date_operation = new Carbon($request->sellDate_invest);
        $operation->date_invest = new Carbon($request->date_invest);
        $operation->user_id = $user->id;
        $operation->treasury_id = $treasuryid;
        $operation->broker_id = $brokerid;
        $operation->save();

        //atualiza o quant no investimento original
        $investUpdate = Invest::findOrFail($request->id);

        if (floatval($request->sellQuant) === floatval($investUpdate->quant)) {
            $investUpdate->liquidated = 1;
        }
        $investUpdate->quant = $investUpdate->quant - $request->sellQuant;
        $investUpdate->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi atualizado.|success'
                        ]);
    }
    public function storeFund(Request $request, Operation $operation)
    {
        $user = Auth::user();
        $quant = $request->quant;
        $this->validate(request(), [
                      'cnpj' => 'required|string|max:25|exists:funds,cnpj',
                      'name' => 'required|string|exists:funds,name',
                      'quant' => 'required|numeric|min:0.01',
                      'price' => 'required|numeric|min:0.0001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
                      'broker_name' => 'required|exists:brokers,name',
                      'sellDate_invest'=> 'required|date|before_or_equal:tomorrow|after:date_invest',
                      'sellPrice' => 'required|numeric|min:0.0001',
                      'sellQuant' => 'required|numeric|min:0.0001|max:' . $quant,
                      'sellBroker_fee' => 'required|numeric|min:0',
                  ], [
                      'cnpj.required' => 'O CNPJ do fundo deve ser inserido.',
                      'cnpj.exists' => 'O CNPJ do fundo deve constar no sistema, caso o CNPJ esteja correto, favor solicitar inclusão do fundo no sistema.',
                      'name.required' => 'O nome do fundo deve ser inserido.',
                      'name.exists' => 'O nome do fundo deve constar no sistema, caso o nome esteja correto, favor solicitar inclusão do fundo no sistema.',
                      'quant.required'  => 'A quantidade é necessária.',
                      'price.required'  => 'O preço é necessário.',
                      'price.min'  => 'O preço deve ser maior que zero.',
                      'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
                      'broker_fee.required'  => 'A corretagem deve ser inserida, mesmo que zero.',
                      'date_invest.required'  => 'A data do investimento deve ser inserida.',
                      'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                      'broker_name.required'  => 'A corretora deve ser inserida.',
                      'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                      'sellDate_invest.required' => 'A data de venda deve ser inserida.',
                      'sellDate_invest.after' => 'A data de venda deve ser depois da compra.',
                      'sellDate_invest.before_or_equal' => 'A data de venda deve ser de hoje ou anterior.',
                      'sellPrice.required'  => 'O preço de venda é necessário.',
                      'sellPrice.min'  => 'O preço de venda deve ser maior que zero.',
                      'sellQuant.required'  => 'A quantidade de venda é necessária.',
                      'sellQuant.min'  => 'A quantidade de venda mínima é 0.',
                      'sellQuant.max'  => 'A quantidade de venda não deve ser maior que a quantidade disponível.',
                      'sellBroker_fee.min'  => 'A corretagem de venda deve ser inserida, mesmo que zero.',
                  ]);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $fundid = DB::table('funds')->where('cnpj', $request->cnpj)->value('id');

        $operation = new Operation;
        $operation->type = 'fund';
        $operation->inv_id = $request->id;
        $operation->quant = $request->sellQuant;
        $operation->quant_orig = $request->quant_orig;
        $operation->price = $request->sellPrice;
        $operation->price_orig = $request->price;
        $operation->broker_fee = $request->sellBroker_fee;
        $operation->broker_fee_orig = $request->broker_fee;
        $operation->date_operation = new Carbon($request->sellDate_invest);
        $operation->date_invest = new Carbon($request->date_invest);
        $operation->user_id = $user->id;
        $operation->fund_id = $fundid;
        $operation->broker_id = $brokerid;
        $operation->save();

        //atualiza o quant no investimento original
        $investUpdate = Invest::findOrFail($request->id);

        if (floatval($request->sellQuant) === floatval($investUpdate->quant)) {
            $investUpdate->liquidated = 1;
        }
        $investUpdate->quant = $investUpdate->quant - $request->sellQuant;
        $investUpdate->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi atualizado.|success'
                        ]);
    }
    public function storeSecurity(Request $request, Operation $operation)
    {
        $user = Auth::user();
        $quant = $request->quant;
        $this->validate(request(), [
                      'name' => 'required|string|max:25|exists:securities,name',
                      'quant' => 'required|numeric|min:0.01',
                      'price' => 'required|numeric|min:0.0001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
                      'rate' => 'required',
                      'broker_name' => 'required|exists:brokers,name',
                      'issuer_name' => 'required|exists:issuers,name',
                      'sellDate_invest'=> 'required|date|before_or_equal:tomorrow|after:date_invest',
                      'sellPrice' => 'required|numeric|min:0.0001',
                      'sellQuant' => 'required|numeric|min:0.0001|max:' . $quant,
                      'sellBroker_fee' => 'required|numeric|min:0',
                  ], [
                      'name.required' => 'O código do título deve ser inserido.',
                      'name.exists' => 'O código do título deve constar no sistema.',
                      'price.required'  => 'A quantidade é necessária.',
                      'quant.required'  => 'O preço é necessário.',
                      'quant.min'  => 'O preço deve ser maior que zero.',
                      'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
                      'broker_fee.required'  => 'A corretagem deve ser inserida, mesmo que zero.',
                      'rate.required'  => 'A taxa deve ser inserida, mesmo que zero.',
                      'date_invest.required'  => 'A data do investimento deve ser inserida.',
                      'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                      'broker_name.required'  => 'A corretora deve ser inserida.',
                      'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                      'issuer_name.required'  => 'A emissora deve ser inserida.',
                      'issuer_name.exists'  => 'A emissora deve estar cadastrada.',
                      'sellDate_invest.required' => 'A data de venda deve ser inserida.',
                      'sellDate_invest.after' => 'A data de venda deve ser depois da compra.',
                      'sellDate_invest.before_or_equal' => 'A data de venda deve ser de hoje ou anterior.',
                      'sellPrice.required'  => 'O preço de venda é necessário.',
                      'sellPrice.min'  => 'O preço de venda deve ser maior que zero.',
                      'sellQuant.required'  => 'A quantidade de venda é necessária.',
                      'sellQuant.min'  => 'A quantidade de venda mínima é 0.',
                      'sellQuant.max'  => 'A quantidade de venda não deve ser maior que a quantidade disponível.',
                      'sellBroker_fee.min'  => 'A corretagem de venda deve ser inserida, mesmo que zero.',
                  ]);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $issuerid = DB::table('issuers')->where('name', $request->issuer_name)->value('id');
        $securityid = DB::table('securities')->where('name', $request->name)->value('id');

        $operation = new Operation;
        $operation->type = 'fund';
        $operation->inv_id = $request->id;
        $operation->quant = $request->sellQuant;
        $operation->quant_orig = $request->quant_orig;
        $operation->price = $request->sellPrice;
        $operation->price_orig = $request->price;
        $operation->broker_fee = $request->sellBroker_fee;
        $operation->broker_fee_orig = $request->broker_fee;
        $operation->date_operation = new Carbon($request->sellDate_invest);
        $operation->date_invest = new Carbon($request->date_invest);
        $operation->user_id = $user->id;
        $operation->security_id = $securityid;
        $operation->broker_id = $brokerid;
        $operation->issuer_id = $issuerid;
        $operation->save();

        //atualiza o quant no investimento original
        $investUpdate = Invest::findOrFail($request->id);

        if (floatval($request->sellQuant) === floatval($investUpdate->quant)) {
            $investUpdate->liquidated = 1;
        }
        $investUpdate->quant = $investUpdate->quant - $request->sellQuant;
        $investUpdate->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi atualizado.|success'
                        ]);
    }
}
