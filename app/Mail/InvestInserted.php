<?php

namespace App\Mail;

use App\Invest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class InvestInserted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
  
    public $invest;
  
    public function __construct(Invest $invest)
    {
        //
      $this->invest = $invest;
      if ($invest->type == 'stock') {
          $invest->type = 'Ação';
        };
        //setlocale(LC_MONETARY, 'en_US');
        $invest->price = number_format($invest->price, 2, ',', '.');
        $invest->quant = number_format($invest->quant, 0, ',', '.');
        //$invest->date_invest = ($invest->date_invest,"Y/m/d H:i:s");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('mails.invest.inserted')
                    ->subject('Investimento inserido')
                    ->with(
                                  [
                                        'dateTimeEmail' => Carbon::now(),
                                  //      'type' => $invest->type,
                                  ]);
    }
}
