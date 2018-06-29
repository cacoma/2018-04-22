<?php

namespace App\Http\Controllers;

use App\Security;
use App\Issuer;
use App\Invest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SecurityController extends Controller
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
        $securities = Security::all();
        return view('securities.index')->with('securities', $securities);
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
            return view('securities.create');
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
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id === 1) {
            $this->validate(request(), [
                // 'symbol' => 'required|string|max:25|unique:securities,symbol',
                'name' => 'required|string|max:25|unique:securities,name',
                'type' => 'required|string|max:255',
                'index' => 'required|string|max:255',
                'ir' => 'required|string|max:255',
                // 'liquidity' => 'required|string|max:255',
                'fgc' => 'required|boolean',
            ], [
                // 'symbol.required' => 'O código do titulo deve ser inserido.',
                // 'symbol.max' => 'O código do titulo deve ter no máximo 9 caracteres.',
                // 'symbol.unique' => 'O código do titulo não deve ser duplicado.',
                'name.required'  => 'O nome do titulo é requerido.',
                'name.max'  => 'O tamanho máximo de texto é 25 caracteres.',
                'type.required'  => 'O tipo do titulo é requerido.',
                'type.max'  => 'O tamanho máximo de texto é 255 caracteres.',
                'index.required'  => 'O nome do titulo é requerido.',
                'index.max'  => 'O tamanho máximo de texto é 255 caracteres.',
                'ir.required'  => 'O nome do titulo é requerido.',
                'ir.max'  => 'O tamanho máximo de texto é 255 caracteres.',
                // 'liquidity.required'  => 'A liquidez é requerida.',
                // 'liquidity.max'  => 'A liquidez é de no máximo 255 caracteres.',
                'fgc.required'  => 'A informação de cobertura do FGC é requerida.',
    ]);
            $security = new security;
            // $security->symbol = strtoupper($request->symbol);
            $security->name = strtoupper($request->name);
            $security->type = strtoupper($request->type);
            $security->index = strtoupper($request->index);
            $security->ir = strtoupper($request->ir);
            // $security->liquidity = strtoupper($request->liquidity);
            $security->fgc = $request->fgc;
            $security->save();
            return response()->json([
                      'type' => 'success',
                      'message' => 'A renda fixa foi inserida.|success'
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
     * @param  \App\Security  $security
     * @return \Illuminate\Http\Response
     */
    public function show(Security $security)
    {
        //
        $security = Security::findOrFail($id);
        return $security;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Security  $security
     * @return \Illuminate\Http\Response
     */
    public function edit(Security $security)
    {
        //
        $security = Security::findOrFail($id);
        return view('securities.edit', compact('security', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Security  $security
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $security = Security::find($id);
            $this->validate(request(), [
              // 'symbol' => 'required|string|max:25|unique:securities,symbol',
              'name' => ['required', 'string', 'max:25', Rule::unique('securities')->ignore($security->name, 'name'),],
              'type' => 'required|string|max:255',
              'index' => 'required|string|max:255',
              'ir' => 'required|string|max:255',
              // 'liquidity' => 'required|string|max:255',
              'fgc' => 'required|boolean',
          ], [
              // 'symbol.required' => 'O código do titulo deve ser inserido.',
              // 'symbol.max' => 'O código do titulo deve ter no máximo 9 caracteres.',
              // 'symbol.unique' => 'O código do titulo não deve ser duplicado.',
              'name.required'  => 'O nome do titulo é requerido.',
              'name.max'  => 'O tamanho máximo de texto é 25 caracteres.',
              'type.required'  => 'O tipo do titulo é requerido.',
              'type.max'  => 'O tamanho máximo de texto é 255 caracteres.',
              'index.required'  => 'O nome do titulo é requerido.',
              'index.max'  => 'O tamanho máximo de texto é 255 caracteres.',
              'ir.required'  => 'O nome do titulo é requerido.',
              'ir.max'  => 'O tamanho máximo de texto é 255 caracteres.',
              // 'liquidity.required'  => 'A liquidez é requerida.',
              // 'liquidity.max'  => 'A liquidez é de no máximo 255 caracteres.',
              'fgc.required'  => 'A informação de cobertura do FGC é requerida.',
  ]);
            // $security->symbol = strtoupper($request->symbol);
            $security->name = strtoupper($request->name);
            $security->type = strtoupper($request->type);
            $security->index = strtoupper($request->index);
            $security->ir = strtoupper($request->ir);
            // $security->liquidity = strtoupper($request->liquidity);
            $security->fgc = $request->fgc;
            $security->save();
            return response()->json([
                      'type' => 'success',
                      'message' => 'A renda fixa foi atualizada.|success'
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
     * @param  \App\Security  $security
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $securityDel = Security::findOrFail($id);
            $securityDel->delete();
            return response()->json([
    'message' => 'Renda fixa deletada.|success'
        ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
                        'message' => 'Permissao invalida.|warning'
                        ], 200);
        }
    }
  
      //funcoes para inserir o investimento em titulos
      public function investstore(Request $request)
    {
        $user = Auth::user();
        //$invest->quant = floatval(preg_replace(',', '.', preg_replace('.', '', $request->quant)));
        $this->validate(request(), [
                      'signal' => [
                                      'required',
                                      Rule::in(['buy', 'sell']),
                                  ],
                      'name' => 'required|string|max:25|exists:securities,name',
                      'quant' => 'required|numeric|min:0.01',
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
        //dd($request);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        $issuerid = DB::table('issuers')->where('name', $request->issuer_name)->value('id');
        //$brokerid = $request->broker()->id;
        $securityid = DB::table('securities')->where('name', $request->name)->value('id');

        $invest = new Invest;
        $invest->type = 'security';
        //$invest->symbol = strtoupper($request->symbol);
        $invest->quant = $request->quant;
        $invest->price = $request->price;
        //$invest->rate = floatval($request->rate);
        $invest->rate = $request->rate;
        $invest->broker_fee = $request->broker_fee;
        $invest->date_invest = new Carbon($request->date_invest);
        $invest->user_id = $user->id;
        $invest->security_id = $securityid;
        $invest->broker_id = $brokerid;
        $invest->issuer_id = $issuerid;
        $invest->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi inserido.|success'
                        ]);
    }
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
        $invest->rate = floatval($invest->rate);
        $invest->quant = $request->quant;
        return view('securities.securityinvestedit', compact('invest', 'id'));
        //return redirect('invests')->with('success', 'Foi ao lugar certo.');
    }
    public function investupdate(Request $request)
    {
        //acha o investimento
        $investUpdate = Invest::findOrFail($request->id);
        //pega o id do user
        $userid = $request->user()->id;
        $user = Auth::user();
        if ($userid === $user->id || $user->role_id === 1) {
            //$request->quant = floatval(preg_replace('/[,]/','.',preg_replace('/[.]/', '',$request->quant)));
            $this->validate(request(), [
            'name' => 'required|string|max:25|exists:securities,name',
            'quant' => 'required|numeric|min:0.01',
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
            //$brokerid = $request->broker()->id;
            $securityid = DB::table('securities')->where('name', $request->name)->value('id');
            //atualiza BD
            $investUpdate->type = 'security';
            //$investUpdate->symbol = strtoupper($request->get('symbol'));
            //$investUpdate->quant = $request->quant;
            $investUpdate->quant = $request->quant;
            $investUpdate->price = floatval($request->price);
            $investUpdate->broker_fee = floatval($request->broker_fee);
            $investUpdate->rate = $request->rate;
            $investUpdate->date_invest = new Carbon($request->date_invest);
            $investUpdate->user_id = $userid;
            $investUpdate->security_id = $securityid;
            $investUpdate->broker_id = $brokerid;
            $investUpdate->issuer_id = $issuerid;
            $investUpdate->save();
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
    public function investdestroy($id)
    {
        //
        $user = Auth::user();
        $investDel = invest::findOrFail($id);
        if ($user->role_id === 1 || $user->id === $investDel->user_id) {
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
