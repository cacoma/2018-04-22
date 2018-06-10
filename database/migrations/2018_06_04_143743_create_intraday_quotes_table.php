<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntradayQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intraday_quotes', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('stock_id')->unsigned();
          $table->decimal('volume',18,2);
          $table->decimal('open',18,2);
          $table->decimal('high',18,2);
          $table->decimal('low',18,2);
          $table->decimal('close',18,2);
          $table->dateTime('timestamp');
          $table->timestamps();
          //foreign keys
          $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intraday_quotes');
    }
}
