<?php

namespace App\Http\Controllers;

use App\Fund;
use App\Invest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FundController extends Controller
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
        $funds = Fund::all();
        return view('funds.index')->with('funds', $funds);
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
            return view('funds.create');
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
                        'name' => 'required|string|max:255',
                        'cnpj' => 'required|string|max:18|min:18|unique:funds,cnpj',
                        'reg_date' => 'required|date',
                        'const_date' => 'required|date',
                        'canc_date' => 'date|nullable',
                                                'sit' => 'required|string|max:255',
                                                'classe' => 'required|string|max:255',
                                                'rentabilidade' => 'string|max:255|nullable',
                        'inv_qual' => 'boolean|nullable',
                        'fundo_exc' => 'boolean|nullable',
                        'fundo_cotas' => 'boolean|nullable',
                        'ir' => 'boolean|nullable',
                                                'taxa_perf' => 'string|max:255|nullable',
                                                'diretor' => 'required|string|max:255',
                                                'admin' => 'required|string|max:255',
                                                'gestor' => 'required|string|max:255',
                                                'auditor' => 'required|string|max:255',
                    ], [
                        'name.required'  => 'O nome do fundo é requerido.',
                        'cnpj.required' => 'O código do fundo deve ser inserido.',
                        'cnpj.max' => 'O código do fundo deve ter no máximo 18 caracteres.',
                        'cnpj.min' => 'O código do fundo deve ter no minimo 18 caracteres.',
                        'cnpj.unique' => 'O código do fundo não deve ser duplicado.',
                        'reg_date.required'  => 'A data de registro é requerida.',
                        'const_date.required'  => 'A data de constituição é requerida.',
                        'sit.required'  => 'A situação é requerida.',
                        'classe.required'  => 'A classe é requerida.',
                        'diretor.required'  => 'O diretor é requerido.',
                        'admin.required'  => 'O administrador é requerido.',
                        'gestor.required'  => 'O gestor é requerido.',
                        'auditor.required'  => 'O auditor é requerido.',
                        //'cupon_date.date'  => 'A data de pagamento de juros deve ser válida.',
            ]);
            $fund = new Fund;
            $fund->name = $request->name;
            $fund->cnpj = $request->cnpj;
            $fund->reg_date = $request->reg_date;
            $fund->const_date = $request->const_date;
            $fund->canc_date = $request->canc_date;
            $fund->sit = $request->sit;
            $fund->classe = $request->classe;
            $fund->rentabilidade = $request->rentabilidade;
            $fund->inv_qual = $request->inv_qual;
            $fund->fundo_exc = $request->fundo_exc;
            $fund->fundo_cotas = $request->fundo_cotas;
            $fund->ir = $request->ir;
            $fund->taxa_perf = $request->taxa_perf;
            $fund->diretor = $request->diretor;
            $fund->admin = $request->admin;
            $fund->gestor = $request->gestor;
            $fund->auditor = $request->auditor;
            $fund->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O fundo foi inserido.|success'
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
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function show(Fund $fund)
    {
        //
        $fund = Fund::findOrFail($id);
        return $fund;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function edit(Fund $fund)
    {
        //
        $fund = Fund::findOrFail($id);
        return view('funds.edit', compact('fund', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $fund = Fund::find($id);
            $this->validate(request(), [
                        'name' => 'required|string|max:255',
                        'cnpj' => ['required', 'string', 'max:18', 'min:18', Rule::unique('funds')->ignore($fund->cnpj, 'cnpj'),],
                        'reg_date' => 'required|date',
                        'const_date' => 'required|date',
                        'canc_date' => 'date|nullable',
                                                'sit' => 'required|string|max:255',
                                                'classe' => 'required|string|max:255',
                                                'rentabilidade' => 'string|max:255|nullable',
                        'inv_qual' => 'boolean|nullable',
                        'fundo_exc' => 'boolean|nullable',
                        'fundo_cotas' => 'boolean|nullable',
                        'ir' => 'boolean|nullable',
                                                'taxa_perf' => 'string|max:255|nullable',
                                                'diretor' => 'required|string|max:255',
                                                'admin' => 'required|string|max:255',
                                                'gestor' => 'required|string|max:255',
                                                'auditor' => 'required|string|max:255',
                    ], [
                        'name.required'  => 'O nome do fundo é requerido.',
                        'cnpj.required' => 'O código do fundo deve ser inserido.',
                        'cnpj.max' => 'O código do fundo deve ter no máximo 18 caracteres.',
                        'cnpj.min' => 'O código do fundo deve ter no minimo 18 caracteres.',
                        'cnpj.unique' => 'O código do fundo não deve ser duplicado.',
                        'reg_date.required'  => 'A data de registro é requerida.',
                        'const_date.required'  => 'A data de constituição é requerida.',
                        'sit.required'  => 'A situação é requerida.',
                        'classe.required'  => 'A classe é requerida.',
                        'diretor.required'  => 'O diretor é requerido.',
                        'admin.required'  => 'O administrador é requerido.',
                        'gestor.required'  => 'O gestor é requerido.',
                        'auditor.required'  => 'O auditor é requerido.',
                        //'cupon_date.date'  => 'A data de pagamento de juros deve ser válida.',
            ]);
            $fund->name = $request->name;
            $fund->cnpj = $request->cnpj;
            $fund->reg_date = $request->reg_date;
            $fund->const_date = $request->const_date;
            $fund->canc_date = $request->canc_date;
            $fund->sit = $request->sit;
            $fund->classe = $request->classe;
            $fund->rentabilidade = $request->rentabilidade;
            $fund->inv_qual = $request->inv_qual;
            $fund->fundo_exc = $request->fundo_exc;
            $fund->fundo_cotas = $request->fundo_cotas;
            $fund->ir = $request->ir;
            $fund->taxa_perf = $request->taxa_perf;
            $fund->diretor = $request->diretor;
            $fund->admin = $request->admin;
            $fund->gestor = $request->gestor;
            $fund->auditor = $request->auditor;
            $fund->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O fundo foi atualizado.|success'
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
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $fundDel = Fund::findOrFail($id);
            $fundDel->delete();
            return response()->json([
            'message' => 'Fundo deletado.|success'
                ], 200);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
    public function investstore(Request $request)
    {
        $user = Auth::user();
        $this->validate(request(), [
                      'signal' => [
                                      'required',
                                      Rule::in(['buy', 'sell']),
                                  ],
                      'cnpj' => 'required|string|max:25|exists:funds,cnpj',
                      'name' => 'required|string|exists:funds,name',
                      'quant' => 'required|numeric|min:0.01',
                      'price' => 'required|numeric|min:0.0001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
//                       'rate' => 'required',
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
//                       'rate.required'  => 'A taxa deve ser inserida, mesmo que zero.',
                      'date_invest.required'  => 'A data do investimento deve ser inserida.',
                      'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                      'broker_name.required'  => 'A corretora deve ser inserida.',
                      'broker_name.exists'  => 'A corretora deve estar cadastrada.',
                  ]);
        if ($request->signal === 'sell') {
            $request->quant = 0 - $request->quant;
        };
        //dd($request);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $fundid = DB::table('funds')->where('cnpj', $request->cnpj)->value('id');

        $invest = new Invest;
        $invest->type = 'fund';
        //$invest->symbol = strtoupper($request->symbol);
        $invest->quant = $request->quant;
        $invest->price = $request->price;
        //$invest->rate = floatval($request->rate);
//         $invest->rate = $request->rate;
        $invest->broker_fee = $request->broker_fee;
        $invest->date_invest = new Carbon($request->date_invest);
        $invest->liquidated = 0;
        $invest->user_id = $user->id;
        $invest->fund_id = $fundid;
        $invest->broker_id = $brokerid;
        $invest->save();

        return response()->json([
                            'type' => 'success',
                            'message' => 'O investimento foi inserido.|success'
                        ]);
    }
    public function investedit($id)
    {
        //abre a tela onde vai ser feita a edicao
//         $invest = invest::with('broker')->findOrFail($id);
//         $invest->broker_name = $invest->broker->name;
//         unset($invest->broker);
//         //$invest->price = strtr($invest->price, array('.' => ','));
//         $invest->price = floatval($invest->price);
//         //$invest->broker_fee = strtr($invest->broker_fee, array('.' => ','));
//         $invest->broker_fee = floatval($invest->broker_fee);
//         $invest->rate = floatval($invest->rate);
//         $invest->quant = $request->quant;
//         return view('funds.fundinvestedit', compact('invest', 'id'));
//         //return redirect('invests')->with('success', 'Foi ao lugar certo.');
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
            'cnpj' => 'required|string|max:25|exists:funds,cnpj',
            'name' => 'required|string|exists:funds,name',
            'quant' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.0001',
            'broker_fee' => 'required|numeric|min:0',
            'date_invest' => 'required|before:tomorrow',
//             'rate' => 'required',
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
//                       'rate.required'  => 'A taxa deve ser inserida, mesmo que zero.',
                      'date_invest.required'  => 'A data do investimento deve ser inserida.',
                      'date_invest.before'  => 'A data do investimento deve ser menor que o dia de hoje.',
                      'broker_name.required'  => 'A corretora deve ser inserida.',
                      'broker_name.exists'  => 'A corretora deve estar cadastrada.',
          ]);
            //pega info de broker e stock id
            $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
            //$brokerid = $request->broker()->id;
            $fundid = DB::table('funds')->where('cnpj', $request->cnpj)->value('id');
            //atualiza BD
            $investUpdate->type = 'fund';
            //$investUpdate->symbol = strtoupper($request->get('symbol'));
            //$investUpdate->quant = $request->quant;
            $investUpdate->quant = $request->quant;
            $investUpdate->price = floatval($request->price);
            $investUpdate->broker_fee = floatval($request->broker_fee);
//             $investUpdate->rate = $request->rate;
            $investUpdate->date_invest = new Carbon($request->date_invest);
            $investUpdate->user_id = $userid;
            $investUpdate->fund_id = $fundid;
            $investUpdate->broker_id = $brokerid;
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
                                  'message' => 'Acesso negado.|warning'
                                  ], 200);
        }
    }
}
