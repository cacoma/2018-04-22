<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_securities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invest_id')->nullable()->unsigned();
            $table->integer('security_id')->nullable()->unsigned();
            $table->decimal('price_operation', 12, 2);
            $table->decimal('price_invest', 12, 2);
            $table->decimal('quant_operation', 12, 6);
            $table->decimal('quant_invest', 12, 6);
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->dateTime('date_operation');
            $table->dateTime('date_invest');
            $table->decimal('broker_fee_operation', 12, 2);
            $table->decimal('broker_fee_invest', 12, 2);
            $table->integer('broker_id')->unsigned();
            $table->integer('issuer_id')->nullable()->unsigned();
            $table->integer('receiver')->nullable()->unsigned();
         });
      Schema::table('operation_securities', function($table) {
            //foreign keys
            $table->foreign('invest_id')->references('id')->on('invests');
            $table->foreign('security_id')->references('id')->on('securities');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broker_id')->references('id')->on('brokers');
            $table->foreign('issuer_id')->references('id')->on('issuers');
            DB::statement('ALTER TABLE operation_securities ADD total_operation DECIMAL(18,2) AS (price_operation * quant_operation - broker_fee_operation)');
            //DB::statement('ALTER TABLE operation_stocks ADD total_invest DECIMAL(18,2) AS ((price_orig * quant) + ((broker_fee_orig/quant_orig) * broker_fee))');
            DB::statement('ALTER TABLE operation_securities ADD duration_days int(18) AS (TIMESTAMPDIFF(day, date_invest,date_operation))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_securities');
    }
}
