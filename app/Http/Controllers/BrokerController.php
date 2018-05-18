<?php
namespace App\Http\Controllers;

use App\Broker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class BrokerController extends Controller
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
        $brokers = Broker::all();
        return view('brokers.index')->with('brokers', $brokers);
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
            return view('brokers.create');
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
                        'cnpj' => 'required|string|max:18|min:18|unique:brokers,cnpj',
                    ],[
                        'name.required' => 'O nome da corretora deve ser inserido.',
                        'cnpj.required' => 'O CNPJ da corretora é necessário.',
                        'cnpj.max' => 'O CNPJ da corretora deve ter no máximo 18 caracteres.',
                        'cnpj.min' => 'O CNPJ da corretora deve ter no minimo 18 caracteres.',
                        'cnpj.unique' => 'O CNPJ da corretora nao pode ser repetido.'
          ]);
          $broker = new Broker;
          $broker->name = $request->name;
          $broker->cnpj = $request->cnpj;
          $broker->save();
//             Broker::create([
//             'name' => $broker['name'],
//                         'cnpj' => $broker['cnpj'],
//                         'created_at' => Carbon::now(),
//             ]);
        return response()->json([
                              'type' => 'success',
                              'message' => 'A corretora foi inserida.|success'
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
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $broker = Broker::findOrFail($id);
        return $broker;
    }
    public function detail($id)
    {
        //
        $broker = Broker::findOrFail($id);
        return view('brokers.detail', array('broker' => $broker));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $broker = Broker::findOrFail($id);
        return view('brokers.edit', compact('broker', 'id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $brokerUpdate = Broker::find($id);
            $this->validate(request(), [
                        'name' => ['required', 'string', 'max:255'],
                        'cnpj' => ['required', 'string', 'max:18', 'min:18', Rule::unique('brokers')->ignore($brokerUpdate->cnpj, 'cnpj'),],
                        ],[
                        'name.required' => 'O nome da corretora deve ser inserido.',
                        'cnpj.required' => 'O CNPJ da corretora é necessário.',
                        'cnpj.max' => 'O CNPJ da corretora deve ter no máximo 18 caracteres.',
                        'cnpj.min' => 'O CNPJ da corretora deve ter no minimo 18 caracteres.',
                        'cnpj.unique' => 'O CNPJ da corretora nao pode ser repetido.'
          ]);
            $brokerUpdate->name = $request->get('name');
            $brokerUpdate->cnpj = $request->get('cnpj');
            $brokerUpdate->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'A corretora foi atualizada.|success'
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
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $brokerDel = Broker::findOrFail($id);
            $brokerDel->delete();
            return response()->json([
                              'type' => 'success',
                              'message' => 'A corretora foi deletada.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
}