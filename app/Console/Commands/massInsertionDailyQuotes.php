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

    public function errorshow($message, $progress, $stock)
    {
        $progress->clear();
        $this->info($message . $stock . PHP_EOL);
        sleep(3);
        $progress->display();
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
        // ini_set('display_errors', 0);
        // ini_set('log_errors', 1);
        // error_reporting(E_ALL);
        $log = new \stdClass;
        $log->message = ["Os resultados foram: |warning"];
        $log->command = "massInsertion:dailyQuotes";
        $log->dateUpdate = Carbon::now()->toDateTimeString();
        $log->success = [];
        $log->fail = [];
        $stockids = DB::table('stocks')->select('id', 'symbol')->get();

        $this->info("Iniciando...\n");

        $progress = $this->output->createProgressBar(count($stockids));

        //$progress->setFormat("%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% -- %message% (%stockidsymbol%)");
        $progress->setFormat("<fg=white;bg=cyan> %message% (%stockidsymbol%)</>\n%current%/%max% [%bar%] %percent:3s%%\n🏁  %estimated:-20s%  %memory:20s%");
        $progress->setBarCharacter('<fg=green>⚬</>');
        $progress->setEmptyBarCharacter("<fg=red>⚬</>");
        $progress->setProgressCharacter("<fg=green>➤</>");
//         // the finished part of the bar
//         $progress->setBarCharacter('<comment>=</comment>');

//         // the unfinished part of the bar
//         $progress->setEmptyBarCharacter(' ');

//         // the progress character
//         $progress->setProgressCharacter('|');

//         // the bar width
//         $progress->setBarWidth(50);

        foreach ($stockids as &$stockid) {
            //primeiro testa se tem algum registro, se nao houver insere todos os dados
            if (DB::table('daily_quotes')->where('stock_id', $stockid->id)->doesntExist()) {
                $progress->setMessage('Importando do zero o stock: ');
                $progress->setMessage($stockid->symbol, 'stockidsymbol');
                $dataCSV = '';
                try {
                    $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $stockid->symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                    if (!$handle) {
                        //$progress->setMessage('Erro ao abrir arquivo, (!$handle) ');
                        //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                         $progress->clear();
//                         $this->info('Erro ao abrir arquivo, (!$handle) ' . $stockid->symbol . PHP_EOL);
//                         sleep(3);
//                         $progress->display();
                        self::errorshow('Erro ao abrir arquivo, (!$handle) ', $progress, $stockid->symbol);
                        array_push($log->fail, $stockid->symbol . " : Erro ao abrir arquivo, 1a fase. Info: " . $e);
                        //fclose($handle);
                        $handle = false;
                        //sleep(1);
                        throw new Exception("Nao foi possivel abrir o arquivo.");
                    }
                } catch (\Exception $e) {
                    //$progress->setMessage('Erro ao abrir arquivo, (!$handle), no catch. ');
                    //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                     $progress->clear();
//                     $this->info('Erro ao abrir arquivo, (!$handle), no catch. ' . $stockid->symbol . PHP_EOL);
//                     sleep(3);
//                     $progress->display();
                    self::errorshow('Erro ao abrir arquivo, (!$handle), no catch. ', $progress, $stockid->symbol);
                    array_push($log->fail, $stockid->symbol . " : Erro ao abrir arquivo, 1a fase, no catch. Info: " . $e);
                    //fclose($handle);
                    //sleep(1);
                    $handle = false;
                }
                //testa se o alpha vantage esta funcionando
                if ($handle !== false	&& is_resource($handle)) {
                    $data = fgetcsv($handle);
                    if ($data[0] === '{') {
                        //$progress->setMessage('problema com o formato do arquivo, pode ser Error message: Invalid API call.');
                        //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                         $progress->clear();
//                         $this->info('problema com o formato do arquivo, pode ser Error message: Invalid API call. ' . $stockid->symbol . PHP_EOL);
//                         sleep(3);
//                         $progress->display();
                        self::errorshow('problema com o arquivo, Error message: Invalid API call. Pode ser que a acao nao exista: ', $progress, $stockid->symbol);
                        array_push($log->fail, $stockid->symbol . " : problema com o arquivo, 'Error message: Invalid API call'. Pode ser que a acao nao exista: ");
                        fclose($handle);
                        //sleep(1);
                        continue;
                    } else {
                        //while para cadastrar todos os dados
                        while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                            try {
                                $dataCSV = Carbon::parse($data[0]);
                            } catch (\Exception $e) {
                                //$progress->setMessage('Carbon nao conseguiu fazer parse da data do arquivo. ');
                                //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                                 $progress->clear();
//                                 $this->info('Carbon nao conseguiu fazer parse da data do arquivo. ' . $stockid->symbol . PHP_EOL);
//                                 sleep(3);
//                                 $progress->display();
                                self::errorshow('Carbon nao conseguiu fazer parse da data do arquivo: ', $progress, $stockid->symbol);
                                array_push($log->fail, $stockid->symbol . " : Carbon nao consegue fazer parse em data do arquivo : " . $dataCSV . "| erro: " . $e);
                                fclose($handle);
                                //sleep(1);
                                continue;
                            }
                            if ($data[0] == 'Error Message: "Invalid API call. Please retry or visit the documentation (https://www.alphavantage.co/documentation/) for TIME_SERIES_DAILY."') {
                                //$progress->setMessage('problema com o formato do arquivo: Error message: Invalid API call.');
                                //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                                 $progress->clear();
//                                 $this->info('problema com o formato do arquivo, Error message: Invalid API call: ' . $stockid->symbol . PHP_EOL);
//                                 sleep(3);
//                                 $progress->display();
                                self::errorshow('problema com o arquivo: Error message: Invalid API call: ', $progress, $stockid->symbol);
                                array_push($log->fail, $stockid->symbol . " : 'Error message: Invalid API call'.");
                                fclose($handle);
                                //sleep(1);
                                continue;
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
                    }
                    try {
                        fclose($handle);
                    } catch (\Exception $e) {
                        array_push($log->fail, $stockid->symbol . " : nao foi possivel fazer o fclose " . $e);
                    }
                    array_push($log->success, $stockid->symbol . "<- do zero");
                } else {
                    //nao funcionou a importacao,problema no AV
                    //fclose($handle);
                    //$progress->setMessage('Erro: $handle !== false	&& is_resource($handle) ');
                    //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                     $progress->clear();
//                     $this->info('Erro: $handle !== false	&& is_resource($handle): ' . $stockid->symbol . PHP_EOL);
//                     sleep(3);
//                     $progress->display();
                    self::errorshow('Erro: $handle !== false	&& is_resource($handle): ', $progress, $stockid->symbol);
                    //sleep(1);
                    array_push($log->fail, $stockid->symbol . !" : não foi possível acesso com o AV.");
                }
            } else {
                $progress->setMessage('Stock existe no banco, será inserido somente as datas faltantes: ');
                $progress->setMessage($stockid->symbol, 'stockidsymbol');
                $dataCSV = '';
//                 $dataOBD = new \stdClass;
//                 $dataBD = '';
                try {
                    $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $stockid->symbol . "&apikey=" . env('MIX_APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                    if (!$handle) {
                        //$progress->setMessage('Erro ao abrir arquivo, (!$handle) ');
                        //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                         $progress->clear();
//                         $this->info('Erro ao abrir arquivo, (!$handle): ' . $stockid->symbol . PHP_EOL);
//                         sleep(3);
//                         $progress->display();
                        self::errorshow('Erro ao abrir arquivo, (!$handle) ', $progress, $stockid->symbol);
                        array_push($log->fail, $stockid->symbol . " : Erro ao abrir arquivo, mensagem dentro do if. Info: " . $e);
                        $handle = false;
                        sleep(1);
                        try {
                            fclose($handle);
                        } catch (\Exception $e) {
                            array_push($log->fail, $stockid->symbol . " : nao foi possivel fazer o fclose " . $e);
                            //sleep(1);
                        }
                        throw new Exception("Nao foi possivel abrir o arquivo.");
                    }
                } catch (\Exception $e) {
                    //$progress->setMessage('Erro ao abrir arquivo, (!$handle) ');
                    //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                     $progress->clear();
//                     $this->info('Erro ao abrir arquivo, (!$handle): ' . $stockid->symbol . PHP_EOL);
//                     sleep(3);
//                     $progress->display();
//                     sleep(1);
                    self::errorshow('Erro ao abrir arquivo, (!$handle) ', $progress, $stockid->symbol);
                    array_push($log->fail, $stockid->symbol . " : Erro ao abrir arquivo, mensagem do catch. Info: " . $e);
                    //fclose($handle);
                    $handle = false;
                }
                if ($handle !== false	&& is_resource($handle)) {
                    //esse comando exclui a primeira linha do csv
                    $data = fgetcsv($handle);
                    if ($data[0] === '{') {
                        //$progress->setMessage('problema com o formato do arquivo, pode ser Error message: Invalid API call.');
//                         $progress->clear();
//                         $this->info('problema com o formato do arquivo, pode ser Error message: Invalid API call: ' . $stockid->symbol . PHP_EOL);
//                         sleep(3);
//                         $progress->display();
                        self::errorshow('problema com o formato do arquivo, pode ser Error message: Invalid API call: ', $progress, $stockid->symbol);
                        //$progress->setMessage($stockid->symbol, 'stockidsymbol');
                        array_push($log->fail, $stockid->symbol . " : problema com o arquivo, 'Error message: Invalid API call'. Pode ser que a acao nao exista: ");
                        fclose($handle);
                        //sleep(1);
                        continue;
                    } else {
                        while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                            if ($data[0] == 'Error Message: "Invalid API call. Please retry or visit the documentation (https://www.alphavantage.co/documentation/) for TIME_SERIES_DAILY."') {
                                //$progress->setMessage('problema com o formato do arquivo: Error message: Invalid API call.');
                                //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                                 $progress->clear();
//                                 $this->info('problema com o formato do arquivo, Error message: Invalid API call: ' . $stockid->symbol . PHP_EOL);
//                                  sleep(3);
//                                  $progress->display();
                                self::errorshow('problema com o formato do arquivo: Error message: Invalid API call: ', $progress, $stockid->symbol);
                                array_push($log->fail, $stockid->symbol . " : problema com o arquivo, 'Error message: Invalid API call'.");
                                fclose($handle);
                                //sleep(1);
                                continue;
                            } else {
                                try {
                                    $dataCSV = Carbon::parse($data[0]);
                                } catch (\Exception $e) {
                                    //$progress->setMessage('Carbon nao conseguiu fazer parse da data do arquivo. ');
                                    //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                                     $progress->clear();
//                                     $this->info('Carbon nao conseguiu fazer parse da data do arquivo: ' . $stockid->symbol . PHP_EOL);
//                                     sleep(3);
//                                     $progress->display();
                                    self::errorshow('Carbon nao conseguiu fazer parse da data do arquivo: ', $progress, $stockid->symbol);
                                    array_push($log->fail, $stockid->symbol . " : Carbon nao consegue fazer parse em data do arquivo : " . $dataCSV . "| erro: " . $e);
                                    fclose($handle);
                                    //sleep(1);
                                    continue;
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
                                    //array_push($log->fail, $stockid->symbol . " : data a ja esta no BD. " . $dataCSV);
                                }
                            }
                        } //ref ao while
                    }
                } else {
                    //nao funcionou a importacao,problema no AV
                    //fclose($handle);
                    array_push($log->fail, $stockid->symbol . " : nao foi possível acesso ao AV.");
                    //$progress->setMessage('Erro: $handle !== false	&& is_resource($handle) ');
                    //$progress->setMessage($stockid->symbol, 'stockidsymbol');
//                     $progress->clear();
//                     $this->info('Erro: $handle !== false	&& is_resource($handle): ' . $stockid->symbol . PHP_EOL);
//                     sleep(3);
//                     $progress->display();
                    self::errorshow('Erro: $handle !== false	&& is_resource($handle): ', $progress, $stockid->symbol);
                }
                try {
                    fclose($handle);
                } catch (\Exception $e) {
                    array_push($log->fail, $stockid->symbol . " : nao foi possivel fazer o fclose " . $e);
                }
            }
            $progress->advance();
            sleep(1);
        }
        Log::info("Execução da importação em massa : " . json_encode($log));
        //Mail::to("rcaziraghi@gmail.com")->send(new DailyQuotesUpdated(json_encode($log)));
        Mail::to("rcaziraghi@gmail.com")->bcc("rodrigobertinmachado@gmail.com")->send(new DailyQuotesUpdated($log));
        $progress->setMessage('Finalizado!');
        $progress->setMessage('=D', 'stockidsymbol');
        $progress->finish();
        //$this->info("\n Finalizado! \n");
//         return response()->json([
//                     'message' => $log
//                    ]);
    }
}
