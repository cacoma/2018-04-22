<?php

namespace App\Http\Controllers;

use App\Index;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IndexController extends Controller
{
    //se usuario estiver registrado, pode visualizar, caso nao, redirecionado para tela de login
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
        $indices = Index::all();
        return view('indices.index')->with('indices', $indices);
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
            return view('indices.create');
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
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
        $this->validate(request(), [
                        'name' => 'required|string|max:255|unique:indices,name',
                        'type' => 'required|string|max:255',
                        'unit' => 'required|string|max:255',
                        'bc_code' => 'required|string|max:255',
                    ],[
                        'name.required' => 'O nome do indice deve ser inserido.',
                        'name.unique' => 'O nome do indice nao pode ser repetido.',
                        'type.required' => 'O tipo do indice é necessário.',
                        'type.max' => 'O tipo do indice deve ter no máximo 255 caracteres.',                        
                        'unit.required' => 'A unidade de medida é necessária.',
                        'unit.max' => 'A unidade de medida deve ter no máximo 255 caracteres.',
                        'bc_code.required' => 'O código do Banco Central é necessário.',
                        'bc_code.max' => 'O código do Banco Central deve ter no máximo 255 caracteres.',
          ]);
          $index = new Index;
          $index->name = $request->name;
          $index->type = $request->type;
          $index->unit = $request->unit;
          $index->bc_code = $request->bc_code;
          $index->save();
        return response()->json([
                              'type' => 'success',
                              'message' => 'O indice foi inserido.|success'
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
     * @param  \App\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $index = Index::findOrFail($id);
        return $index;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $index = Index::findOrFail($id);
        return view('indices.edit', compact('index', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $indexUpdate = Index::find($id);
            $this->validate(request(), [
                        'name' => ['required', 'string', 'max:255', Rule::unique('indices')->ignore($indexUpdate->name, 'name'),],
                        'type' => ['required', 'string', 'max:255'],
                        'unit' => ['required', 'string', 'max:255'],
                        'bc_code' => ['required', 'string', 'max:255'],
                        ],[
                        'name.required' => 'O nome do indice deve ser inserido.',
                        'name.unique' => 'O nome do indice nao pode ser repetido.',
                        'type.required' => 'O tipo do indice é necessário.',
                        'type.max' => 'O tipo do indice deve ter no máximo 255 caracteres.',                        
                        'unit.required' => 'A unidade de medida é necessária.',
                        'unit.max' => 'A unidade de medida deve ter no máximo 255 caracteres.',
                        'bc_code.required' => 'O código do Banco Central é necessário.',
                        'bc_code.max' => 'O código do Banco Central deve ter no máximo 255 caracteres.',
          ]);
            $indexUpdate->name = $request->get('name');
            $indexUpdate->type = $request->get('type');
            $indexUpdate->unit = $request->get('unit');
            $indexUpdate->bc_code = $request->get('bc_code');
            $indexUpdate->save();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O indice foi atualizado.|success'
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
     * @param  \App\Index  $index
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $indexDel = Index::findOrFail($id);
            $indexDel->delete();
            return response()->json([
                              'type' => 'success',
                              'message' => 'O indice foi deletado.|success'
                          ]);
        } else {
            return response()->json([
                                'message' => 'Permissao invalida.|warning'
                                ], 200);
        }
    }
}
