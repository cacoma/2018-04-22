<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UsersRequest;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
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
      //if (Gate::allows('admin-only', auth()->user())) {
      $user = Auth::user();
        //dono e admin somente podem alterar
      //if ($user->role_id == '1') {
      if (Gate::allows('admin')) {
      //if ($user->can('view')) {
        $users = User::all();
        return view('users.index')->with('users', $users);
      } else {
//          return response()->json([
//             'message' => 'Acesso negado.|warning'
//         ], 200);
        return redirect('home')->with('flash', 'Acesso negado.|warning');
      }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return redirect('users')->with('flash', ['type' => 'earning', 'message'=>'Usuarios devem ser criados pela pagina principal.']);
        //return response()->json(['key' => 'flash', 'message' => 'Usuarios devem ser criados pela pagina principal.']);
        return redirect()->back()->with('flash', 'Usuarios devem ser criados pela pagina principal.|warning');
        //with('flash', ['type' => 'earning', 'message'=>'Usuarios devem ser criados pela pagina principal.']);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        return view('users.edit', compact('user', 'id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    // public function update(Request $request)
    {
        $userUpdate = User::find($id);
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $this->validate(request(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userUpdate->email, 'email'),],
                'role_id' => ['required'],
              ], [
              'name.required' => 'O nome do usuário deve ser inserido.',
              'email.unique' => 'O endereço de email não pode ser duplicado.',
              'email.required'  => 'O email é requerido.',
              'email.email'  => 'O email deve ser válido.',
              'role_id.required'  => 'A permissão deve ser inserida.'
          ]);
            $userUpdate->name = $request->get('name');
            $userUpdate->email = $request->get('email');
            $userUpdate->role_id = $request->get('role_id');
            $userUpdate->save();
            return response()->json([
            'message' => 'Usuario atualizado com sucesso.|success'
        ], 200);
        //\Session::flash('flash','Usuario atualizado com sucesso.|success');
        //Session::flash('flash','Usuario atualizado com sucesso.|success');
        //return redirect()->back()->with('flash','Usuario atualizado com sucesso.|success');
        // $request->session()->flash('flash','Usuario atualizado com sucesso.|success');
        } else {
            return response()->json([
            'message' => 'Acesso negado.|warning'
        ], 200);
            //return redirect()->with('flash', 'Permissão negada.|warning');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // $userDel = User::find($id);
        // $user = Auth::user();
        // //dono e admin somente podem alterar
        // if ($user->role_id == '1') {
        //     $userDel->delete();
        //     return response()->json([
        //          'message' => 'Usuario deletado com sucesso!|success'
        //      ], 200);
        // } else {
        //     return response()->json([
        //     'message' => 'Acesso negado.|danger'
        // ], 200);
        // }
        return redirect()->back()->with('flash', 'Usuarios devem ser desativados pela edição.|warning');
    }
  
    //mostrar o profile para o usuario
  
    public function profileshow()
    {
        //
      $user = Auth::user();
      return view('users.profileedit', compact('user'));
    }
  
    public function profileupdate(Request $request, $id)
      // public function update(Request $request)
      {
          //$userUpdate = User::find($id);
          $user = Auth::user();
          //dono e admin somente podem alterar
          if ($user->id == $request->get('id')) {
              $this->validate(request(), [
                  'name' => ['required', 'string', 'max:255'],
                  'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userUpdate->email, 'email'),],
                  //'role_id' => ['required'],
                ], [
                'name.required' => 'O nome do usuário deve ser inserido.',
                'email.unique' => 'O endereço de email não pode ser duplicado.',
                'email.required'  => 'O email é requerido.',
                'email.email'  => 'O email deve ser válido.',
                //'role_id.required'  => 'A permissão deve ser inserida.'
            ]);
              $user->name = $request->get('name');
              $user->email = $request->get('email');
              //$user->role_id = $request->get('role_id');
              $user->save();
              return response()->json([
              'message' => 'Usuario atualizado com sucesso.|success'
          ], 200);

          } else {
              return response()->json([
              'message' => 'Acesso negado.|warning'
          ], 200);
          }
      }
}
