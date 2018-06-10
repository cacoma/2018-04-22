<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewConsolidated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //View pra mostrar os investimentos consolidados por acao - Machado 29/05/2018 21:00hs
    //     DB::statement('CREATE VIEW consolidated AS SELECT i.user_id,u.name,i.stock_id,s.symbol,avg(i.price) as avgprice,
    //       sum(i.quant) as sumquant,(avg(i.price) * sum(i.quant)) as total
    //       from invests i inner join users u on i.user_id = u.id
    //       inner join stocks s on i.stock_id = s.id
    //       group by 1,3;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Remover view dos investimentos consolidados - Machado 29/05/2018 20:55hs
        // DB::statement('DROP VIEW IF EXISTS pizza');
    }
}
