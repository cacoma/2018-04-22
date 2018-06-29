<?php

namespace App\Http\Controllers;

use App\Issuer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IssuerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
        $issuers = Issuer::all();
        return view('issuers.index')->with('issuers', $issuers);
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
            return view('issuers.create');
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
        //$request = $this->formatcnpj($request);
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
        $this->validate(request(), [
                        'name' => 'required|string|max:255',
                        'cnpj' => 'required|string|max:18|min:18|unique:issuers,cnpj',
                    ],[
                        'name.required' => 'O nome da emissora deve ser inserido.',
                        'cnpj.required' => 'O CNPJ da emissora é necessário.',
                        'cnpj.max' => 'O CNPJ da emissora deve ter no máximo 18 caracteres.',
                        'cnpj.min' => 'O CNPJ da emissora deve ter no minimo 18 caracteres.',
                        'cnpj.unique' => 'O CNPJ da emissora nao pode ser repetido.'
          ]);
          $issuer = new Issuer;
          $issuer->name = $request->name;
          $issuer->cnpj = $request->cnpj;
          $issuer->save();
//             Issuer::create([
//             'name' => $issuer['name'],
//                         'cnpj' => $issuer['cnpj'],
//                         'created_at' => Carbon::now(),
//             ]);
        return response()->json([
                              'type' => 'success',
                              'message' => 'A emissora foi inserida.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
    private function formatcnpj($request)
    {
        // nao uso mais
        $request['cnpj'] = (int)preg_replace("/[^0-9,.]/", "", $request['cnpj']);
        return $request;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\issuer  $issuer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $issuer = Issuer::findOrFail($id);
        return $issuer;
    }
    public function detail($id)
    {
        //
        $issuer = Issuer::findOrFail($id);
        return view('issuers.detail', array('issuer' => $issuer));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\issuer  $issuer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $issuer = Issuer::findOrFail($id);
        return view('issuers.edit', compact('issuer', 'id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\issuer  $issuer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $issuerUpdate = Issuer::find($id);
            $this->validate(request(), [
                        'name' => ['required', 'string', 'max:255'],
                        'cnpj' => ['required', 'string', 'max:18', 'min:18', Rule::unique('issuers')->ignore($issuerUpdate->cnpj, 'cnpj'),],
                        ],[
                        'name.required' => 'O nome da emissora deve ser inserido.',
                        'cnpj.required' => 'O CNPJ da emissora é necessário.',
                        'cnpj.max' => 'O CNPJ da emissora deve ter no máximo 18 caracteres.',
                        'cnpj.min' => 'O CNPJ da emissora deve ter no minimo 18 caracteres.',
                        'cnpj.unique' => 'O CNPJ da emissora nao pode ser repetido.'
          ]);
            $issuerUpdate->name = $request->get('name');
            $issuerUpdate->cnpj = $request->get('cnpj');
            $issuerUpdate->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'A emissora foi atualizada.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|error'
                                ], 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\issuer  $issuer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $issuerDel = Issuer::findOrFail($id);
            $issuerDel->delete();
            return response()->json([
                              'type' => 'success',
                              'message' => 'A emissora foi deletada.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
}
