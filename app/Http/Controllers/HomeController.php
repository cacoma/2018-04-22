<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MonthlyQuote;
use App\DailyQuote;
use App\Invest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user = Auth::user();
          //variavel que leva os dados do chart pie (pizza)
         $pie = new \stdClass;
         $portPerfP = new \stdClass;
      
         $invests = Invest::with('stock')->where('user_id', $user->id)->get();
      
      
        //pega todos os investimentos AGRUPADOS do usuario para chart de pie (pizza)
        $results = Invest::groupBy('stock_id')->with('stock')
          ->selectRaw('sum(total) as total,sum(quant) as quant, avg(price) as avgprice, stock_id')
          ->where('user_id', $user->id)->get();
       
      
        $monthlyQuotes = monthlyQuote::with('stock')
            ->where('stock_id', '<', '4')
                ->whereDate('timestamp', '>', Carbon::now()->subMonth(12))
                      ->orderBy('timestamp', 'asc')->get();
        
      
      //variavel que busca os dados de todas as acoes que o usuario tem, para chart
       $portPerf = Invest::groupBy('stock_id')->with('stock')
          ->selectRaw('stock_id')
          ->where('user_id', $user->id)->get();
      
      
      
      foreach ($portPerf as &$portPer) {
            //inicializa as variaves que irao formatar os dados de acordo com o requerido pelo vue-chartkick:
            //{name: "xxx", data: {"data":"valor","data":"valor}}
            $tempTS = array();
            $tempCS = array();
            
            //acerta o nome do stock
            $portPer->name = $portPer->stock->symbol;
        
            //depois retira o objeto de dentro do objeto
            unset($portPer->stock);           
            
        //busca as cotacoes dos ultimos 12 meses para cada acao que o usuario possui em carteira
//           $portPer->temp = monthlyQuote::where('stock_id', '=', $portPer->stock_id)
//               ->selectRaw('timestamp, close')
//                 ->whereDate('timestamp', '>', Carbon::now()->subMonth(12))
//                       ->orderBy('timestamp', 'asc')->get();
        
        $portPer->temp = dailyQuote::where('stock_id', '=', $portPer->stock_id)
              ->selectRaw('timestamp, close')
                ->whereDate('timestamp', '>', Carbon::now()->subDays(30))
                      ->orderBy('timestamp', 'asc')->get();
        
        //insere os dados de data e valor de fechamento nas variaveis temporarias
        foreach($portPer->temp as &$value){
            array_push($tempTS,$value->timestamp);
            array_push($tempCS, $value->close);
          }
        
        //insere os dados da forma solicitada pela ferramenta: 
        $portPer->data = array_combine($tempTS,$tempCS);

           unset($portPer->stock_id);
        }
      
        //acerta a informacao de cotacoes mensais para cada ativo      
        foreach ($monthlyQuotes as $monthlyQuote) {
            //arruma o nome
            $monthlyQuote->stockName = $monthlyQuote->stock->symbol;
            //depois retira o objeto de dentro do objeto
            unset($monthlyQuote->stock);
            //funcao para gerar uma cor diferente para cada id diferente
            $hash = md5('cor' . $monthlyQuote->stock_id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $a = hexdec(substr($hash, 6, 2));
            $monthlyQuote->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
        }
        
        //foreach para tratar invests
        foreach ($invests as &$invest) {
            // retira o objeto, pegando valor antes
            $invest->stockName = $invest->stock->symbol;
            //retira o objeto de dentro do objeto, para renderizar corretamente
            unset($invest->stock);
            if ($invest->type == 'stock') {
                $invest->type = 'Ação';
            }
            //funcao para gerar uma cor diferente para cada id diferente
            $hash = md5('cor' . $invest->stock_id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $a = hexdec(substr($hash, 6, 2));
            $invest->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
        }
      
         //variaveis para modelar os dados para o vue-chartkick, em modo line e bar {dado:valor}
         $tempStockName = array();
         $tempTotal = array();
         $tempPercentage = array();
           
        //foreach para tratar results e inserir dados no pie
        foreach ($results as &$result) {
          
            //pega as ultimas cotacoes mensais da acao
            $result->lastQuote = dailyQuote::where('stock_id', '=', $result->stock_id)
            //$result->lastQuote = monthlyQuote::where('stock_id', '=', $result->stock_id)
            //$result->quote = monthlyQuote::where('stock_id', '=', $result->stock_id)
                //->whereDate('timestamp', '>', Carbon::now()->subMonth(2))
                      ->orderBy('timestamp', 'desc')
                        ->first();
            //pega a ultima ultima (caso nao tenha atualizado por ultimo)
          
            $result->quote = $result->lastQuote->close;
            //retira objeto nao necessario
            //unset($result->lastQuote);
            //faz o calculo do total atualizado
            $result->totalUpdated = floatval(preg_replace("/[^-0-9\.]/", "", $result->quote)) * floatval(preg_replace("/[^-0-9\.]/", "", $result->quant));
            //faz o calculo de % de ganho ou perda
            $result->percentage = (floatval(preg_replace("/[^-0-9\.]/", "", $result->totalUpdated)) / floatval(preg_replace("/[^-0-9\.]/", "", $result->total)) - 1)*100;
            //trata o nome
            $result->stockName = $result->stock->symbol;
            //retira o objeto nao mais necessário
            unset($result->stock);
            //funcao para gerar uma cor diferente para cada id diferente
            $hash = md5('cor' . $result->stock_id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $a = hexdec(substr($hash, 6, 2));
            $result->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
          
            array_push($tempStockName, $result->stockName);
            array_push($tempTotal, $result->total);
            array_push($tempPercentage, number_format((float)$result->percentage, 2, '.', ''));
            //array_push($tempPercentage, $result->percentage);
        }
        //combinacao de arrays para criar uma nova no modelo do vue-chartkick {dado:valor}
        $pie = array_combine($tempStockName,$tempTotal);
        $portPerfP = array_combine($tempStockName,$tempPercentage);
        
        return view('home')->with('results', $results)
                              ->with('invests', $invests)
                              ->with('portPerf', $portPerf)
                                ->with('portPerfP', $portPerfP)
                                ->with('monthlyQuotes', $monthlyQuotes)
                                  ->with('pie',$pie);
    }
}