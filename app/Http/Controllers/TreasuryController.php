<?php

namespace App\Http\Controllers;

use App\Treasury;
use App\Invest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TreasuryController extends Controller
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
        $treasuries = Treasury::all();
        return view('treasuries.index')->with('treasuries', $treasuries);
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
            return view('treasuries.create');
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
        //valida antes de dar o store
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id === 1) {
            $this->validate(request(), [
                        'code' => 'required|string|max:25|unique:treasuries,code',
                        'name' => 'required|string|max:255',
                        'due_date' => 'required|date',
                        'coupon' => 'required|boolean',
                        'coupon_date' => 'nullable',
                    ], [
                        'code.required' => 'O código do titulo deve ser inserido.',
                        'code.max' => 'O código do titulo deve ter no máximo 9 caracteres.',
                        'code.unique' => 'O código do titulo não deve ser duplicado.',
                        'name.required'  => 'O nome do titulo é requerido.',
                        'name.max'  => 'O tamanho máximo de texto é 255 caracteres.',
                        'due_date.required'  => 'A data de vencimento é requerido.',
                        'cupon.required'  => 'É necessário informar se paga juros semestrais.',
                        //'cupon_date.date'  => 'A data de pagamento de juros deve ser válida.',
            ]);
            $treasury = new Treasury;
            $treasury->code = strtoupper($request->code);
            $treasury->name = strtoupper($request->name);
            $treasury->due_date = $request->due_date;
//             if ($request->coupon === 1) {
//               $request->coupon = true;
//             } else {
//               $request->coupon = false;
//             }
            $treasury->coupon = $request->coupon;
            $treasury->coupon_date = $request->coupon_date;
            $treasury->coupon_date2 = $request->coupon_date2;
            $treasury->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O titulo foi inserido.|success'
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
     * @param  \App\Treasury  $treasury
     * @return \Illuminate\Http\Response
     */
    public function show(Treasury $treasury)
    {
        //
        $treasury = Treasury::findOrFail($id);
        return $treasury;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Treasury  $treasury
     * @return \Illuminate\Http\Response
     */
    public function edit(Treasury $treasury)
    {
        //
        $treasury = Treasury::findOrFail($id);
        return view('treasuries.edit', compact('treasury', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Treasury  $treasury
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $treasury = Treasury::find($id);
            $this->validate(request(), [
                        'code' => ['required', 'string', 'max:25', Rule::unique('treasuries')->ignore($treasury->code, 'code'),],
                        'name' => 'required|string|max:255',
                        'due_date' => 'required|date',
                        'coupon' => 'required|boolean',
                        'coupon_date' => 'nullable',
                    ], [
                        'code.required' => 'O código do titulo deve ser inserido.',
                        'code.max' => 'O código do titulo deve ter no máximo 9 caracteres.',
                        'code.unique' => 'O código do titulo não deve ser duplicado.',
                        'name.required'  => 'O nome do titulo é requerido.',
                        'name.max'  => 'O tamanho máximo de texto é 255 caracteres.',
                        'due_date.required'  => 'A data de vencimento é requerido.',
                        'cupon.required'  => 'É necessário informar se paga juros semestrais.',
                    ]);
            $treasury->code = strtoupper($request->code);
            $treasury->name = strtoupper($request->name);
            $treasury->due_date = $request->due_date;
//           if ($request->coupon === 1) {
//               $request->coupon = true;
//             } else {
//               $request->coupon = false;
//             }
            $treasury->coupon = $request->coupon;
            $treasury->coupon_date = $request->coupon_date;
            $treasury->coupon_date2 = $request->coupon_date2;
            $treasury->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O titulo foi atualizado.|success'
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
     * @param  \App\Treasury  $treasury
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $treasuryDel = Treasury::findOrFail($id);
            $treasuryDel->delete();
            return response()->json([
            'message' => 'Título deletado.|success'
                ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
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
                      'code' => 'required|string|max:25|exists:treasuries,code',
                      'quant' => 'required',
                      'price' => 'required|numeric|min:0.0001',
                      'broker_fee' => 'required|numeric|min:0',
                      'date_invest' => 'required|before:tomorrow',
                      'rate' => 'required',
                      'broker_name' => 'required|exists:brokers,name',
                  ], [
                      'signal.required' => 'Favor informar se é compra ou venda.',
                      'code.required' => 'O código do título deve ser inserido.',
                      'code.exists' => 'O código do título deve constar no sistema.',
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
                  ]);
        if ($request->signal === 'sell') {
            $request->quant = 0 - $request->quant;
        };
        //dd($request);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $treasuryid = DB::table('treasuries')->where('code', $request->code)->value('id');

        $invest = new Invest;
        $invest->type = 'treasury';
        //$invest->symbol = strtoupper($request->symbol);
        $invest->quant = floatval($request->quant);
        $invest->price = $request->price;
        $invest->rate = floatval($request->rate);
        $invest->broker_fee = $request->broker_fee;
        $invest->date_invest = new Carbon($request->date_invest);
        $invest->user_id = $user->id;
        $invest->treasury_id = $treasuryid;
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
        $invest = invest::with('broker')->findOrFail($id);
        $invest->broker_name = $invest->broker->name;
        unset($invest->broker);
        //$invest->price = strtr($invest->price, array('.' => ','));
        $invest->price = floatval($invest->price);
        //$invest->broker_fee = strtr($invest->broker_fee, array('.' => ','));
        $invest->broker_fee = floatval($invest->broker_fee);
        $invest->rate = floatval($invest->rate);
        $invest->quant = floatval($invest->quant);
        return view('treasuries.treasuryinvestedit', compact('invest', 'id'));
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
            $this->validate(request(), [
            'code' => 'required|string|max:25|unique:treasuries,code',
            'quant' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0.0001',
            'broker_fee' => 'required|numeric|min:0',
            'date_invest' => 'required|before:tomorrow',
            'rate' => 'required|numeric|min:0',
            'broker_name' => 'required|exists:brokers,name',
        ], [
            'signal.required' => 'Favor informar se é compra ou venda.',
            'code.required' => 'O código do título deve ser inserido.',
            'code.exists' => 'O código do título deve constar no sistema.',
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
          ]);
            //pega info de broker e stock id
            $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
            //$brokerid = $request->broker()->id;
            $treasuryid = DB::table('treasuries')->where('code', $request->code)->value('id');
            //atualiza BD
            $investUpdate->type = 'stock';
            //$investUpdate->symbol = strtoupper($request->get('symbol'));
            $investUpdate->quant = $request->get('quant');
            $investUpdate->price = $request->get('price');
            $investUpdate->broker_fee = $request->get('broker_fee');
            $investUpdate->rate = $request->get('rate');
            $investUpdate->date_invest = new Carbon($request->get('date_invest'));
            $investUpdate->user_id = $userid;
            $investUpdate->treasury_id = $treasuryid;
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
                                  'message' => 'Permissao invalida.|warning'
                                  ], 200);
        }
    }
}
