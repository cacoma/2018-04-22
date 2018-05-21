<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('symbol');
            $table->integer('stock_id')->unsigned();
            $table->decimal('price', 12, 4);
            $table->decimal('quant', 12, 2);
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->dateTime('date_invest');
            $table->decimal('broker_fee', 12, 2);
            $table->integer('broker_id')->unsigned();
            //$table->decimal('total', 18, 4);


            //foreign keys
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broker_id')->references('id')->on('brokers');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invests');
    }
}
