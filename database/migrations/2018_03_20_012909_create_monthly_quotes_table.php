<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateMonthlyQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_id')->unsigned();
            $table->decimal('open',18,4);
            $table->decimal('volume',18,4);
            $table->dateTime('timestamp');
            $table->decimal('high',18,4);
            $table->decimal('low',18,4);
            $table->decimal('close',18,4);
            $table->timestamps();
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
        Schema::dropIfExists('monthly_quotes');
    }
}