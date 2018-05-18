<?php

namespace App\Mail;

use App\DailyQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class DailyQuotesUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
      
    public $log;
    
    public function __construct($log)
    {
        //
      $this->log = $log;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        //return $this->view('view.name');
         return $this->view('mails.dailyquotesupdated')
                      ->subject('Relatório de DailyQuotes')
//         return $this ->view('mails.dailyquotesupdated')
                      //->text('mails.dailyquotesupdated_plain')
                      ->with(
                        [
                              'dateTimeEmail' => Carbon::now(),
                              //'testVarTwo' => '2',
                        ]);
    }
}
