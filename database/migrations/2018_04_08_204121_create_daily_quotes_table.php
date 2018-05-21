<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateDailyQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_quotes', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('stock_id')->unsigned();
          //$table->integer('stock_id');
          $table->decimal('volume',18,4);
          $table->decimal('open',18,4);
          $table->decimal('high',18,4);
          $table->decimal('low',18,4);
          $table->decimal('close',18,4);
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
        Schema::dropIfExists('daily_quotes');
    }
}
