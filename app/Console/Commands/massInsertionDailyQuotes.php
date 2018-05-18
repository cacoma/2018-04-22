<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DailyQuote;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\DailyQuotesUpdated;
use Illuminate\Support\Facades\Mail;

class massInsertionDailyQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'massInsertion:dailyQuotes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para buscar dados no AV e popular o BD.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        //$user = Auth::user();
        //variavel que carregará os logs da execucao
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        error_reporting(E_ALL);
        $log = new \stdClass;
        $log->message = ["Os resultados foram: |warning"];
        $log->command = "massInsertion:DailyQuotes";
        $log->dateUpdate = Carbon::now()->toDateTimeString();
        $log->success = [];
        $log->fail = [];
        $stockids = DB::table('stocks')->select('id', 'symbol')->get();

        foreach ($stockids as &$stockid) {
            //primeiro testa se tem algum registro, se nao houver insere todos os dados
            if (DB::table('daily_quotes')->where('stock_id', $stockid->id)->doesntExist()) {
                $dataCSV = '';
                try {
                    $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $stockid->symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                } catch (\Exception $e) {
                    array_push($log->fail, $stockid->symbol . " : Erro ao abrir arquivo. Info: " . $e);
                    $handle = false;
                    fclose($handle);
                }
                //testa se o alpha vantage esta funcionando
                if ($handle !== false	&& is_resource($handle)) {
                    fgetcsv($handle);
                    //while para cadastrar todos os dados
                    while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                        try {
                            $dataCSV = Carbon::parse($data[0]);
                        } catch (\Exception $e) {
                            array_push($log->fail, $stockid->symbol . " : Carbon nao consegue fazer parse em data do arquivo : " . $dataCSV . "| erro: " . $e);
                            fclose($handle);
                            break;
                        }
                        if ($data[0] == 'Error Message: "Invalid API call. Please retry or visit the documentation (https://www.alphavantage.co/documentation/) for TIME_SERIES_DAILY."') {
                            array_push($log->fail, $stockid->symbol . " : problema com o arquivo, 'Error message: Invalid API call'.");
                            fclose($handle);
                            break;
                        } else {
                            $dailyQuotes = new DailyQuote();
                            $dailyQuotes->stock_id = $stockid->id;
                            $dailyQuotes->timestamp = $data[0];
                            $dailyQuotes->open = $data[1];
                            $dailyQuotes->high = $data[2];
                            $dailyQuotes->low = $data[3];
                            $dailyQuotes->close = $data[4];
                            $dailyQuotes->volume = $data[5];
                            $dailyQuotes->save();
                        }
                    }
                    fclose($handle);
                    array_push($log->success, $stockid->symbol . "<- do zero");
                } else {
                    //nao funcionou a importacao,problema no AV
                    fclose($handle);
                    array_push($log->fail, $stockid->symbol . !" : não foi possível acesso com o AV.");
                }
            } else {
                $dataCSV = '';
//                 $dataOBD = new \stdClass;
//                 $dataBD = '';
                try {
                    $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $stockid->symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r') or die('Could not open file');
                } catch (\Exception $e) {
                    array_push($log->fail, $stockid->symbol . " : Erro ao abrir arquivo. Info: " . $e);
                    $handle = false;
                    fclose($handle);
                }
                if ($handle !== false	&& is_resource($handle)) {
                    //esse comando exclui a primeira linha do csv
                    fgetcsv($handle);
                    while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                        if ($data[0] == 'Error Message: "Invalid API call. Please retry or visit the documentation (https://www.alphavantage.co/documentation/) for TIME_SERIES_DAILY."') {
                            array_push($log->fail, $stockid->symbol . " : problema com o arquivo, 'Error message: Invalid API call'.");
                            fclose($handle);
                            break;
                        } else {
                            try {
                                $dataCSV = Carbon::parse($data[0]);
                            } catch (\Exception $e) {
                                array_push($log->fail, $stockid->symbol . " : Carbon nao consegue fazer parse em data do arquivo : " . $dataCSV . "| erro: " . $e);
                                fclose($handle);
                                break;
                            }
                            if (DB::table('daily_quotes')->where('stock_id', $stockid->id)->whereDate('timestamp', $dataCSV)->doesntExist()) {
                                $dailyQuotes = new DailyQuote();
                                $dailyQuotes->stock_id = $stockid->id;
                                $dailyQuotes->timestamp = $data[0];
                                $dailyQuotes->open = $data[1];
                                $dailyQuotes->high = $data[2];
                                $dailyQuotes->low = $data[3];
                                $dailyQuotes->close = $data[4];
                                $dailyQuotes->volume = $data[5];
                                $dailyQuotes->save();
                                array_push($log->success, $stockid->symbol . " para data: " . $dataCSV);
                            } else {
                                array_push($log->fail, $stockid->symbol . " : data a ja esta no BD. " . $dataCSV);
                            }
                        }
                    } //ref ao while
                    fclose($handle);
                } else {
                    //nao funcionou a importacao,problema no AV
                    fclose($handle);
                    array_push($log->fail, $stockid->symbol . " : nao foi possível acesso ao AV.");
                }
            }
            sleep(1);
        }
        Log::info("Execução da importação em massa : " . json_encode($log));
        //Mail::to("rcaziraghi@gmail.com")->send(new DailyQuotesUpdated(json_encode($log)));
        Mail::to("rcaziraghi@gmail.com")->bcc("rodrigobertinmachado@gmail.com")->send(new DailyQuotesUpdated($log));
        return response()->json([
                    'message' => $log
                   ]);
    }
}
