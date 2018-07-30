<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invest_securities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('security_id')->nullable()->unsigned();
            $table->decimal('price', 12, 2);
            $table->decimal('quant', 12, 6);
            $table->decimal('quant_orig', 12, 6);
            $table->decimal('rate', 12, 6)->nullable();
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->dateTime('date_invest');
            $table->decimal('broker_fee', 12, 2);
            $table->integer('broker_id')->unsigned();
            $table->integer('issuer_id')->nullable()->unsigned();
            $table->integer('index_id')->nullable()->unsigned();
            $table->boolean('liquidated');
            //foreign keys
            $table->foreign('security_id')->references('id')->on('securities');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broker_id')->references('id')->on('brokers');
            $table->foreign('issuer_id')->references('id')->on('issuers');
            $table->foreign('index_id')->references('id')->on('indices');
        });
       Schema::table('invest_securities', function ($table) {
            DB::statement('ALTER TABLE invest_securities ADD total DECIMAL(18,2) AS (price * quant)');
            DB::statement('ALTER TABLE invest_securities ADD total_orig DECIMAL(18,2) AS (price * quant_orig)');
          // :statement('ALTER TABLE invest_securities ADD duration_days int(18) AS (TIMESTAMPDIFF(day, date_invest,NOW()))');
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
        Schema::dropIfExists('invest_securities');
    }
}
