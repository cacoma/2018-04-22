<?php

namespace App\Console\Commands;

ini_set("allow_url_fopen", 1);


use Illuminate\Console\Command;
use App\IntradayQuote;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\IntradayQuotesUpdated;
use Illuminate\Support\Facades\Mail;

class massInsertionIntradayQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'massInsertion:intradayQuotes';

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


    public function errorshow($message, $progress, $stock)
    {
        $progress->clear();
        $this->info($message . $stock . PHP_EOL);
        sleep(3);
        $progress->display();
    }
  
    public function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $log = new \stdClass;
        $log->message = ["Os resultados foram: |warning"];
        $log->command = "massInsertion:intradayQuotes";
        $log->dateUpdate = Carbon::now()->toDateTimeString();
        $log->success = [];
        $log->fail = [];
        $json = new \stdClass;
        $obj = new \stdClass;
        $stockids = DB::table('stocks')->select('id', 'symbol')->get();

        $this->info("Iniciando...\n");

        //Setting the timeout properly without messing with ini values: 
        //https://secure.php.net/manual/pt_BR/function.file-get-contents.php
      
        $ctx = stream_context_create(array( 
                'http' => array( 
                    'method'=>"GET",
                    'timeout' => 10 
                    ) 
                ) 
            ); 

        foreach ($stockids as &$stockid) {
            $i=0;
            $imported = false;
            //primeiro testa se tem algum registro, se nao houver insere todos os dados

            $dataCSV = '';
            if ($i < 3 || $imported === true) {
                try {
                    echo "\r\n";
                    echo "Buscando: " . $stockid->symbol ."\r\n";
                    
                  $url = "https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=" . $stockid->symbol . "&interval=15min&apikey=" . env('MIX_APLHAVANTAGE_APIKEY');
                  echo "Url: " . $url ."\r\n";
                                 
                    $json = file_get_contents(self::myUrlEncode($url), false, $ctx); 
                    $obj = json_decode($json);
                    //echo $obj->{'Meta Data'}->{'1. Information'};
                  
                    if ($json === false) { 
                       echo "Json False" . "\r\n";
                    }
                    //echo json_encode($obj);
                  //echo $obj->{'Meta Data'}->{'1. Information'};
                } catch (\Exception $e) {
                    echo $e;
                   
                    //self::errorshow('Erro ao abrir arquivo, (!$handle) ', $progress, $stockid->symbol);
                };
              
                $i++;
                echo "Tentativa: " . $i . "\r\n";
                //echo $json . "\r\n";
                sleep(3);
            

            if (array_key_exists('Error Message', $obj)) {
                echo "Error Message: invalid API call" . "\r\n";
                echo $obj->{'Error Message'}. "\r\n";
                continue;
            }
            if (array_key_exists('Information', $obj)) {
                echo "Information" . "\r\n";
                echo $obj->{'Information'}. "\r\n";
                continue;
            }
            if (array_key_exists('Time Series (15min)', $obj)) {
//                 echo "Mensagem: " . "\r\n";
//                 echo json_encode($obj) . "\r\n";
//                 continue;
//             } else {
            //if ($i < 4) {
                //echo "\r\n" .json_encode($obj) . "\r\n";;
                //$element = json_decode($json, true);
                $progress = $this->output->createProgressBar($elementCount = count(json_decode($json, true)));
                //echo $elementCount . "\r\n";
                $progress->setFormat("<fg=white;bg=cyan> %message% (%stockidsymbol%)</>\n%current%/%max% [%bar%] %percent:3s%%\n🏁  %estimated:-20s%  %memory:20s%");
                $progress->setBarCharacter('<fg=green>⚬</>');
                $progress->setEmptyBarCharacter("<fg=red>⚬</>");
                $progress->setProgressCharacter("<fg=green>➤</>");

                $progress->setMessage('Importando: ');
                $progress->setMessage($stockid->symbol, 'stockidsymbol');

                try {
                    //$items = json_decode($json, true);

                    foreach ($obj->{'Time Series (15min)'} as $key => $value) {
                        if (DB::table('intraday_quotes')->where('stock_id', $stockid->id)->where('timestamp', $key)->doesntExist()) {
//                       echo "Nao existe" . "\r\n";
//                       echo $key . "\r\n";
                            $intradayQuotes = new IntradayQuote();
                            $intradayQuotes->stock_id = $stockid->id;
                            $intradayQuotes->timestamp = $key;
                            //echo "\r\n" . $key ."\r\n";
                            $intradayQuotes->open = $value->{'1. open'};
                            //echo $value->{'1. open'} ."\r\n";
                            $intradayQuotes->high = $value->{'2. high'};
                            $intradayQuotes->low = $value->{'3. low'};
                            $intradayQuotes->close = $value->{'4. close'};
                            $intradayQuotes->volume = $value->{'5. volume'};
                            $intradayQuotes->save();
                            $progress->advance();
                        } else {
//                       echo "Existe" . "\r\n";
//                       echo $key . "\r\n";
                        }
                    };
                    $imported = true;
                    $progress->finish();
                    sleep(3);
                } catch (\Exception $e) {
                    echo "Nao foi possivel buscar.". "\r\n";
                    //echo $e;
                }
//             } else { //quando tentou mais de 3 vezes
//                 echo "Não foi possivel recuperar os dados do AV.". "\r\n";
            }
          
          } 
//                                 if (DB::table('intraday_quotes')->where('stock_id', $stockid->id)->whereDate('timestamp', $dataCSV)->doesntExist()) {
//                                     $intradayQuotes = new IntradayQuote();
//                                     $intradayQuotes->stock_id = $stockid->id;
//                                     $intradayQuotes->timestamp = $data[0];
//                                     $intradayQuotes->open = $data[1];
//                                     $intradayQuotes->high = $data[2];
//                                     $intradayQuotes->low = $data[3];
//                                     $intradayQuotes->close = $data[4];
//                                     $intradayQuotes->volume = $data[5];
//                                     $intradayQuotes->save();
//                                     $progress->advance();
//                                     array_push($log->success, $stockid->symbol . " para data: " . $dataCSV);

//             $progress->finish();
//             sleep(1);
//         }
//         Log::info("Execução da importação em massa : " . json_encode($log));
//         Mail::to("rcaziraghi@gmail.com")->bcc("rodrigobertinmachado@gmail.com")->send(new IntradayQuotesUpdated($log));
//         $progress->setMessage('Finalizado!');
//         $progress->setMessage('=D', 'stockidsymbol');
//         $progress->finish();
//         return response()->json([
//                     'message' => $log
//                    ]);
        };
    }
}
