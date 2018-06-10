<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class IntradayQuotesUpdated extends Mailable
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
        return $this->view('mails.intradayquotesupdated')
                      ->subject('Relatório de IntradayQuotes')
                      ->with(
                        [
                              'dateTimeEmail' => Carbon::now(),
                              //'testVarTwo' => '2',
                        ]);
    }
}
