<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inv_id')->nullable()->unsigned();
            $table->string('type');
            $table->integer('stock_id')->nullable()->unsigned();
            $table->integer('treasury_id')->nullable()->unsigned();
            $table->integer('security_id')->nullable()->unsigned();
            $table->integer('fund_id')->nullable()->unsigned();
            $table->decimal('price', 12, 2);
            $table->decimal('price_orig', 12, 2);
            $table->decimal('rate', 12, 6)->nullable();
            $table->decimal('quant', 12, 6);
            $table->decimal('quant_orig', 12, 6);
//             $table->decimal('total_orig', 12, 2);
//             $table->decimal('total', 12, 2);
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->dateTime('date_invest');
            $table->dateTime('date_operation');
            $table->decimal('broker_fee', 12, 2);
            $table->decimal('broker_fee_orig', 12, 2);
            $table->integer('broker_id')->unsigned();
            $table->integer('issuer_id')->nullable()->unsigned();
            $table->integer('receiver')->nullable()->unsigned();
         });
      Schema::table('operations', function($table) {
            //foreign keys
            $table->foreign('inv_id')->references('id')->on('invests');
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('treasury_id')->references('id')->on('treasuries');
            $table->foreign('security_id')->references('id')->on('securities');
            $table->foreign('fund_id')->references('id')->on('funds');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broker_id')->references('id')->on('brokers');
            $table->foreign('issuer_id')->references('id')->on('issuers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
}
