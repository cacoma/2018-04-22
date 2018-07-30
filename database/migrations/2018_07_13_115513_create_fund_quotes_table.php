<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_quotes', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('fund_id')->references('id')->on('indices');
            $table->integer('fund_id')->unsigned();
            $table->string('cnpj', 18);
            $table->dateTime('comp_date');
            $table->decimal('total_cart',18,4);
            $table->decimal('quote',18,4);
            $table->decimal('patrim_liq',18,4);
            $table->decimal('capta_dia',18,4)->nullable();
            $table->decimal('resg_dia',18,4)->nullable();
            $table->decimal('quotistas',18,0)->nullable();
            $table->timestamps();
        });
      Schema::table('fund_quotes', function($table) {
            //foreign keys
            $table->foreign('fund_id')->references('id')->on('indices');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fund_quotes');
    }
}
