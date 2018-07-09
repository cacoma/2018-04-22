<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //comando para importar precos no final do dia
        $schedule->command('massInsertion:dailyQuotes')
          ->timezone('America/Sao_Paulo')
          ->dailyAt('18:00')
//         ->weekdays()
//           ->hourly()
//           ->timezone('America/Sao_Paulo')
//           ->between('8:00', '18:00')
          ->after(function () {
             Log::info("Execução da importação em massa programada");
         });
         $schedule->exec('node /home/ubuntu/scraping/getTreasuries.js')
           ->hourlyAt(18)
           ->between('8:00', '18:00')
           ->sendOutputTo('/var/www/html/storage/logs/treasuryScrape2.log')
           ->emailOutputTo('rcaziraghi@gmail.com')
           ->withoutOverlapping();         
      $schedule->exec('node /home/ubuntu/scraping/getIndices.js')
          ->dailyAt('18:15')
           ->sendOutputTo('/var/www/html/storage/logs/IndicesScrape.log')
           ->emailOutputTo('rcaziraghi@gmail.com')
           ->withoutOverlapping();
//            ->after(function () {
//              Log::info("Execução da importação de títulos programada.");
//          })
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
