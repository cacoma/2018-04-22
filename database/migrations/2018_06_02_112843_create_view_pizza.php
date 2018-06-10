<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPizza extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //View pra mostrar o grafico de pizza da tela inicial - Machado 02/06/2018 11:38hs
        DB::statement('CREATE VIEW pizza AS
          SELECT i.id,i.user_id,s.symbol,i.stock_id,i.price,i.quant,i.total,
            DATE(i.date_invest) as investido,d.close,
            ROUND(i.quant*d.close,2) as total_atual,DATE(d.timestamp) as atual,
            ROUND((close/price-1)*100,2) as dif_porcent,
            ROUND(i.quant*d.close-i.total,2) as dif_reais
          FROM invests i inner join stocks s on i.stock_id = s.id
            inner join daily_quotes d on i.stock_id = d.stock_id ;'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Remover view do grafico de pizza - Machado 02/06/2018 11:35hs
        DB::statement('DROP VIEW IF EXISTS pizza');
    }
}
