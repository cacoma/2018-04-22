<?php

namespace App\Http\Controllers;

use App\investSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvestSecurityController extends Controller
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
                      'name' => 'required|string|max:25|exists:securities,name',
                      'quant' => 'required|numeric|min:0.0001',
                      'price' => 'required|numeric|min:0.0001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
                      'rate' => 'required',
                      'broker_name' => 'required|exists:brokers,name',
                      'issuer_name' => 'required|exists:issuers,name',
                  ], [
                      'signal.required' => 'Favor informar se é compra ou venda.',
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
                  ]);
        if ($request->signal === 'sell') {
            $request->quant = 0 - $request->quant;
        };
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $issuerid = DB::table('issuers')->where('name', $request->issuer_name)->value('id');
        $security = DB::table('securities')->where('name', $request->name)->get();
        

// id	int(10) unsigned Auto Increment	
// type	varchar(255)	
// security_id	int(10) unsigned NULL	
// price	decimal(12,2)	
// quant	decimal(12,6)	
// quant_orig	decimal(12,6)	
// rate	decimal(12,6) NULL	
// created_at	timestamp NULL	
// updated_at	timestamp NULL	
// user_id	int(10) unsigned	
// date_invest	datetime	
// broker_fee	decimal(12,2)	
// broker_id	int(10) unsigned	
// issuer_id	int(10) unsigned NULL	
// index_id	int(10) unsigned NULL	
// liquidated	tinyint(1)	
// total	decimal(18,2) NULL	
// total_orig	decimal(18,2) NULL	
// deleted_at	timestamp NULL      
      
        $investSecurity = new investSecurity;
        $investSecurity->type = $security->type;
        $investSecurity->security_id = $security->id;
        $investSecurity->price = $request->price;
        $investSecurity->quant = $request->quant;
        $investSecurity->quant_orig = $request->quant;
        $investSecurity->rate = $request->rate;
        $investSecurity->user_id = $user->id;
        $investSecurity->date_invest = new Carbon($request->date_invest);
        $investSecurity->broker_fee = $request->broker_fee;
        $investSecurity->broker_id = $brokerid;
        $investSecurity->issuer_id = $issuerid;
        $investSecurity->index_id = $security->index_id;
        $investSecurity->liquidated = 0;
        $investSecurity->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi inserido.|success'
                        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\investSecurity  $investSecurity
     * @return \Illuminate\Http\Response
     */
    public function show(investSecurity $investSecurity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\investSecurity  $investSecurity
     * @return \Illuminate\Http\Response
     */
    public function edit(investSecurity $investSecurity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\investSecurity  $investSecurity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, investSecurity $investSecurity)
    {
      //acha o investimento
        $investSecurityUpdate = investSecurity::findOrFail($request->id);
        //pega o id do user
        $user = Auth::user();
        if ($request->user()->id === $user->id || $user->role_id === 1) {
            $this->validate(request(), [
            'name' => 'required|string|max:25|exists:securities,name',
            'quant' => 'required|numeric|min:0.0001',
            'price' => 'required|numeric|min:0.0001',
            'broker_fee' => 'required|numeric|min:0',
            'date_invest' => 'required|before:tomorrow',
            'rate' => 'required',
            'broker_name' => 'required|exists:brokers,name',
            'issuer_name' => 'required|exists:issuers,name',
        ], [
            'signal.required' => 'Favor informar se é compra ou venda.',
            'name.required' => 'O código do título deve ser inserido.',
            'name.exists' => 'O código do título deve constar no sistema.',
            'quant.required'  => 'A quantidade é necessária.',
            'price.required'  => 'O preço é necessário.',
            'price.min'  => 'O preço deve ser maior que zero.',
            'broker_fee.min'  => 'A corretagem deve ser inserida, mesmo que zero.',
            'broker_fee.required'  => 'A corretagem deve ser inserida, mesmo que zero.',
            'rate.min'  => 'A taxa deve ser inserida, mesmo que zero.',
            'rate.required'  => 'A taxa deve ser inserida, mesmo que zero.',
            'date_invest.required'  => 'A data do investimento deve ser inserida.',
            'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
            'broker_name.required'  => 'A corretora deve ser inserida.',
            'broker_name.exists'  => 'A corretora deve estar cadastrada.',
             'issuer_name.required'  => 'A emissora deve ser inserida.',
            'issuer_name.exists'  => 'A emissora deve estar cadastrada.',
          ]);
            //pega info de broker e stock id
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $issuerid = DB::table('issuers')->where('name', $request->issuer_name)->value('id');
        $security = DB::table('securities')->where('name', $request->name)->get();
        
          
 // id	int(10) unsigned Auto Increment	
// type	varchar(255)	
// security_id	int(10) unsigned NULL	
// price	decimal(12,2)	
// quant	decimal(12,6)	
// quant_orig	decimal(12,6)	
// rate	decimal(12,6) NULL	
// created_at	timestamp NULL	
// updated_at	timestamp NULL	
// user_id	int(10) unsigned	
// date_invest	datetime	
// broker_fee	decimal(12,2)	
// broker_id	int(10) unsigned	
// issuer_id	int(10) unsigned NULL	
// index_id	int(10) unsigned NULL	
// liquidated	tinyint(1)	
// total	decimal(18,2) NULL	
// total_orig	decimal(18,2) NULL	
// deleted_at	timestamp NULL           
          
            //atualiza BD
        $investSecurity->type = $security->type;
        $investSecurity->security_id = $security->id;
        $investSecurity->price = $request->price;
        $investSecurity->quant = $request->quant;
        $investSecurity->quant_orig = $request->quant;
        $investSecurity->rate = $request->rate;
        $investSecurity->user_id = $user->id;
        $investSecurity->date_invest = new Carbon($request->date_invest);
        $investSecurity->broker_fee = $request->broker_fee;
        $investSecurity->broker_id = $brokerid;
        $investSecurity->issuer_id = $issuerid;
        $investSecurity->index_id = $security->index_id;
        $investSecurity->liquidated = 0;
        $investSecurity->save();
          
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\investSecurity  $investSecurity
     * @return \Illuminate\Http\Response
     */
    public function destroy(investSecurity $investSecurity)
    {
        //
        $user = Auth::user();
        $investSecurityDel = invest::findOrFail($id);
        if ($user->role_id === 1 || $user->id === $investSecurityDel->user_id) {
            $investSecurityDel->delete();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O investimento foi deletado.|success'
                          ]);
        } else {
            return response()->json([
                                  'message' => 'Acesso negado.|warning'
                                  ], 200);
        }
    }
}
