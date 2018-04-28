<?php
namespace App\Http\Controllers;
use App\Invest;
use App\Users;
use App\MonthlyQuote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class InvestController extends Controller
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
        $user = Auth::user();
        if ($user->role_id == '1') {
            $invests = Invest::with(['monthlyQuote' => function ($query) {
                $query->whereDate('timestamp', '>', Carbon::now()->subMonth(2))->latest();
            }, 'broker'])->get();
            foreach ($invests as $key => $value) {
                // 					//ajusta nome do broker
                // 					//ajusta se a cotacao for menor ou maior que o price a forma da célula
                $value->quote = $value->monthlyQuote[0]->close;
                //retira o objeto de dentro do objeto, para renderizar corretamente
                unset($value->monthlyQuote);
                //para passar o nome do broker
                $value->broker_id = $value->broker->name;
                //retira o objeto de dentro do objeto, para renderizar corretamente
                unset($value->broker);
                //retira informacoes que nao queremos renderizar
                unset($value->stock_id);
                if ($value->type == 'stock') {
                    $value->type = 'Ação';
                }
                //calcula porcentagem e insere dado no objeto
                $value->percentage = ($value->quote / $value->price - 1);
                if ($value->price >= $value->quote) {
                    $field = array('percentage' => 'danger');
                    $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
                } elseif ($value->price == $value->quote) {
                    $field = array('percentage' => 'info');
                    $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
                } else {
                    $field = array('percentage' => 'success');
                    $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
                }
            }
            return view('invests.index')->with('invests', $invests);
        //return view('invests.index')->with('invests', $invests);
        } else {
            //essa query abaixo busca dados da tabela dos investimentos e das cotas mensais de cada ativo, e relaciona as duas.
            //depois ele puxa somente os registros que o timestamp, que eh a data real do fechamento, e puxa somente dos ultimos dois meses e a ultima por primeiro.
            //isso se faz para caso nao tenha sido inserido o valor neste mes ainda
            //alem desse relacionamento ele traz somente os investimentos que o id é o do usuario registrado
            $invests = Invest::with(['monthlyQuote' => function ($query) {
                $query->whereDate('timestamp', '>', Carbon::now()->subMonth(2))->latest();
            }, 'broker'])->where('user_id', $user->id)->get();
            foreach ($invests as $key => $value) {
                // 					//ajusta se a cotacao for menor ou maior que o price a forma da célula
                $value->quote = $value->monthlyQuote[0]->close;
                //retira o objeto de dentro do objeto, para renderizar corretamente
                unset($value->monthlyQuote);
                //para passar o nome do broker
                $value->broker_id = $value->broker->name;
                //retira o objeto de dentro do objeto, para renderizar corretamente
                unset($value->broker);
                //retira informacoes que nao queremos renderizar
                unset($value->stock_id);
                unset($value->user_id);
                //ajusta o nome do dado interno
                if ($value->type == 'stock') {
                    $value->type = 'Ação';
                }
                //cria a variavel percentage e atribui valor
                $value->percentage = ($value->quote / $value->price - 1);
                if ($value->price >= $value->quote) {
                    $field = array('percentage' => 'danger');
                    $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
                } elseif ($value->price == $value->quote) {
                    $field = array('percentage' => 'info');
                    $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
                } else {
                    $field = array('percentage' => 'success');
                    $value->_cellVariants = (object) array_merge((array)$value->_cellVariants, (array)$field);
                }
            }
            return view('invests.index')->with('invests', $invests);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //implementar
        return view('invests.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //criacao de investimentos vai ser pelo controlador de cada tipo de investimento,
                //afim de dissipar o codigo para os pontos relevantes
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //implementar,
        $invest = invest::findOrFail($id);
        return $invest;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //edicao de investimentos vai ser pelo controlador de cada tipo de investimento,
                //afim de dissipar o codigo para os pontos relevantes
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update de investimentos vai ser pelo controlador de cada tipo de investimento,
                //afim de dissipar o codigo para os pontos relevantes
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //ainda não decidido se a exclusao do investimento eh por este controller ou pelo controller apropriado
        $invest = Invest::find($id);
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->id == $invest->user_id || $user->role_id == '1') {
            $invest->delete();
            return redirect('invests')->with('success', 'Invest deletado');
        } else {
            return back()->with('error', 'O invest nao pode ser deletado');
        }
    }
    public function indexinvests()
    {
        $user = Auth::user();
        if ($user->role_id == '1') {
            $invests = Invest::with(['monthlyQuote' => function ($query) {
                $query->whereDate('timestamp', '>', Carbon::now()->subMonth(2))->latest();
            }, 'broker'])->get();
            return \Response::json($invests);
        } else {

            //essa query abaixo busca dados da tabela dos investimentos e das cotas mensais de cada ativo, e relaciona as duas.
            //depois ele puxa somente os registros que o timestamp, que eh a data real do fechamento, e puxa somente dos ultimos dois meses e a ultima por primeiro.
            //isso se faz para caso nao tenha sido inserido o valor neste mes ainda
            //alem desse relacionamento ele traz somente os investimentos que o id é o do usuario registrado
            $invests = Invest::with(['monthlyQuote' => function ($query) {
                $query->whereDate('timestamp', '>', Carbon::now()->subMonth(2))->latest();
            }])->where('user_id', $user->id)->get();
            return \Response::json($invests);
        }
    }
}