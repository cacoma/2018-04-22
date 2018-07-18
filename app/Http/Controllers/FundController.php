<?php

namespace App\Http\Controllers;

use App\Fund;
use Illuminate\Http\Request;
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
}
