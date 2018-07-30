<?php

namespace App\Http\Controllers;

use App\investFund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\operationFund;

class InvestFundController extends Controller
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
        $user = Auth::user();
        $this->validate(request(), [
                      'cnpj' => 'required|string|max:25|exists:funds,cnpj',
                      'name' => 'required|string|exists:funds,name',
                      'quant' => 'required|numeric|min:0.00001',
                      'price' => 'required|numeric|min:0.00001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
                      'broker_name' => 'required|exists:brokers,name',
                  ], [
                      'signal.required' => 'Favor informar se é compra ou venda.',
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
                  ]);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $fundid = DB::table('funds')->where('cnpj', $request->cnpj)->value('id');

//       id	int(10) unsigned Auto Increment	
//       fund_id	int(10) unsigned NULL	
//       price	decimal(12,2)	
//       quant	decimal(12,6)	
//       quant_orig	decimal(12,6)	
//       created_at	timestamp NULL	
//       updated_at	timestamp NULL	
//       user_id	int(10) unsigned	
//       date_invest	datetime	
//       broker_fee	decimal(12,2)	
//       broker_id	int(10) unsigned	
//       liquidated	tinyint(1)	
//       total	decimal(18,2) NULL	
//       total_orig	decimal(18,2) NULL	
//       deleted_at	timestamp NULL
      
        $investFund = new investFund;
        $investFund->fund_id = $fundid;
        $investFund->price = $request->price;
        $investFund->quant = $request->quant;
        $investFund->quant_orig = $request->quant;
        $investFund->user_id = $user->id;
        $investFund->date_invest = new Carbon($request->date_invest);
        $investFund->broker_fee = $request->broker_fee;
        $investFund->broker_id = $brokerid;
        $investFund->liquidated = 0;
        $investFund->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi inserido.|success'
                        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\investFund  $investFund
     * @return \Illuminate\Http\Response
     */
    public function show(investFund $investFund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\investFund  $investFund
     * @return \Illuminate\Http\Response
     */
    public function edit(investFund $investFund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\investFund  $investFund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, investFund $investFund)
    {
        //
        //acha o investimento
        $investFundUpdate = investFund::findOrFail($request->id);
        if (operationFund::where('invest_id', '=', $request->id)->doesntExist()) {
        //pega o id do user
        $user = Auth::user();
        if ($request->user()->id === $user->id || $user->role_id === 1) {
            $this->validate(request(), [
            'cnpj' => 'required|string|max:25|exists:funds,cnpj',
            'name' => 'required|string|exists:funds,name',
            'quant' => 'required|numeric|min:0.00001',
            'price' => 'required|numeric|min:0.00001',
            'broker_fee' => 'required|numeric|min:0',
            'date_invest' => 'required|before:tomorrow',
            'broker_name' => 'required|exists:brokers,name',
        ], [
                      'signal.required' => 'Favor informar se é compra ou venda.',
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
          ]);
            //pega info de broker e stock id
            $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
            $fundid = DB::table('funds')->where('cnpj', $request->cnpj)->value('id');
                    
//       id	int(10) unsigned Auto Increment	
//       fund_id	int(10) unsigned NULL	
//       price	decimal(12,2)	
//       quant	decimal(12,6)	
//       quant_orig	decimal(12,6)	
//       created_at	timestamp NULL	
//       updated_at	timestamp NULL	
//       user_id	int(10) unsigned	
//       date_invest	datetime	
//       broker_fee	decimal(12,2)	
//       broker_id	int(10) unsigned	
//       liquidated	tinyint(1)	///-->> nao atualiza aqui, pois trata-se de venda
//       total	decimal(18,2) NULL	
//       total_orig	decimal(18,2) NULL	
//       deleted_at	timestamp NULL
          
            //atualiza BD
            $investFundUpdate->fund_id = $fundid;
            $investFundUpdate->price = $request->price;
            $investFundUpdate->quant = $request->quant;
            $investFundUpdate->quant_orig = $request->quant;
            $investFundUpdate->user_id = $user->id;
            $investFundUpdate->date_invest = new Carbon($request->date_invest);
            $investFundUpdate->broker_fee = $request->broker_fee;
            $investFundUpdate->broker_id = $brokerid;
          
            $investFundUpdate->save();
            //retorna com sucesso
            return response()->json([
                                'type' => 'success',
                                'message' => 'O investimento foi atualizado.|success'
                            ]);
        } else {
            return response()->json([
                                'type' => 'error',
                                'message' => 'Acesso negado.|warning'
                            ]);
        }
        } else {
          return response()->json([
                                'type' => 'error',
                                'message' => 'Não é possivel alterar um investimento com operações realizadas.|warning'
                            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\investFund  $investFund
     * @return \Illuminate\Http\Response
     */
    public function destroy(investFund $investFund)
    {
        //
        $user = Auth::user();
        $investFundDel = investFund::findOrFail($id);
        if (operationFund::where('invest_id', '=', $request->id)->doesntExist()) {
        if ($user->role_id === 1 || $user->id === $investDel->user_id) {
            $investDel->delete();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O investimento foi deletado.|success'
                          ]);
        } else {
            return response()->json([
                                  'message' => 'Permissao invalida.|warning'
                                  ], 200);
        }
       } else {
          return response()->json([
                                'type' => 'error',
                                'message' => 'Não é possivel apagar um investimento com operações realizadas.|warning'
                            ]);
        }
    }
}
