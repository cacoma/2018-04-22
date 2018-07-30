<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invest_funds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fund_id')->nullable()->unsigned();
            $table->decimal('price', 12, 2);
            $table->decimal('quant', 12, 6);
            $table->decimal('quant_orig', 12, 6);
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->dateTime('date_invest');
            $table->decimal('broker_fee', 12, 2);
            $table->integer('broker_id')->unsigned();
            //$table->integer('issuer_id')->nullable()->unsigned();
            $table->boolean('liquidated');
            //foreign keys
            $table->foreign('fund_id')->references('id')->on('funds');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broker_id')->references('id')->on('brokers');
            //$table->foreign('issuer_id')->references('id')->on('issuers');             
        });
         Schema::table('invest_funds', function ($table) {
            DB::statement('ALTER TABLE invest_funds ADD total DECIMAL(18,2) AS (price * quant)');
            DB::statement('ALTER TABLE invest_funds ADD total_orig DECIMAL(18,2) AS (price * quant_orig)');
          // DB::statement('ALTER TABLE invest_funds ADD duration_days int(18) AS (TIMESTAMPDIFF(day, date_invest,NOW()))');
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invest_funds');
    }
}
