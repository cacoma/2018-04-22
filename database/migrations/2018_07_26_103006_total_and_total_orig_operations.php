<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TotalAndTotalOrigOperations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('operations', function (Blueprint $table) {
            //Adicionar campo calculado automaticamente com SQL RAW - Machado 09/04/2018 14:12hs
            DB::statement('ALTER TABLE operations ADD total DECIMAL(18,2) AS (price * quant + broker_fee)');
            DB::statement('ALTER TABLE operations ADD total_orig DECIMAL(18,2) AS ((price_orig * quant) + ((broker_fee_orig/quant_orig) * broker_fee))');
            DB::statement('ALTER TABLE operations ADD duration_days int(18) AS (TIMESTAMPDIFF(day, date_invest,date_operation))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
