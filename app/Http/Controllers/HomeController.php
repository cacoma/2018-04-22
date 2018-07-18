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

        //variavel que leva os dados do chart pie (pizza) com todos os investimentos e nomes
        //pie:Object {
        //'item':'total'
        //}
        // $pie = 1;
        $piesRaw = DB::table('invests_updated')
        ->select(DB::raw('designation, SUM(totalAtual) as total'))
        ->where('userId', $user->id)->groupBy('designation')->get();

        foreach ($piesRaw as &$pieRaw) {
               $pie[$pieRaw->designation] = $pieRaw->total;
              //$pie = (object) array($pieRaw->designation => $pieRaw->total);
          }

        //variavel para trazer retorno de todos os investimentos pelo %
//         portperfp:Object{
//         'nome':'valor%'
//         }
          $portperfpsRaw = DB::table('invests_updated')
          ->select(DB::raw('designation, SUM(totalAtual) as totalAtual, SUM(total) as totalInv, round((((SUM(totalAtual)/SUM(total))-1)*100),2) as difP'))
          ->where('userId', $user->id)->groupBy('designation')->get();
  
          $portPerfP['totalInv']  = 0;
          $portPerfP['totalAtual']  = 0;
      
          foreach ($portperfpsRaw as &$portperfpRaw) {
                 $portPerfP[$portperfpRaw->designation] = $portperfpRaw->difP;
                 $portPerfP['totalInv'] = $portPerfP['totalInv'] + $portperfpRaw->totalInv;
                 $portPerfP['totalAtual'] = $portPerfP['totalAtual'] + $portperfpRaw->totalAtual;
                //$pie = (object) array($pieRaw->designation => $pieRaw->total);
            }
          
          $portPerfP['Portfolio'] = round(((($portPerfP['totalAtual']/$portPerfP['totalInv'])-1)*100), 2);
          unset($portPerfP['totalInv']);
          unset($portPerfP['totalAtual']);

              //variavel para trazer retorno dos investimentos  agrupados pelo tipo, em %
//         portperfp:Object{
//         'tipo':'valor%'
//         }

      
      $piesTypeRaw = DB::table('invests_updated')->select(DB::raw('tipo, sum(totalAtual) as total'))->where('userId', $user->id)->groupBy('tipo')->get();

        foreach ($piesTypeRaw as &$pieTypeRaw) {
               $pieType[$pieTypeRaw->tipo] = $pieTypeRaw->total;
              //$pie = (object) array($pieRaw->designation => $pieRaw->total);
          }
      
      // invests, para trazer o nome, id e tipo dos investimentos para buscar cotacoes no chart interativo
        $invests = DB::table('invests_updated')->select(DB::raw('DISTINCT designation, type, typeId'))->where('userId', $user->id)->get();

        return view('home')
        ->with('invests', $invests)
                               // ->with('portPerf', $portPerf)
                               ->with('pieType', $pieType)
                                ->with('portPerfP', $portPerfP)
                                  ->with('pie', $pie);
                              //    ->with('day', $day);
    }
}
