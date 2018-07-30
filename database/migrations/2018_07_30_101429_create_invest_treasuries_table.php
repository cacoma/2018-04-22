<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestTreasuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invest_treasuries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('treasury_id')->nullable()->unsigned();
            $table->decimal('price', 12, 2);
            $table->decimal('quant', 12, 6);
            $table->decimal('quant_orig', 12, 6);
            $table->decimal('rate', 12, 6)->nullable();
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->dateTime('date_invest');
            $table->decimal('broker_fee', 12, 2);
            $table->integer('broker_id')->unsigned();
            $table->boolean('liquidated');
            //foreign keys
            $table->foreign('treasury_id')->references('id')->on('treasuries');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broker_id')->references('id')->on('brokers');
        });
      Schema::table('invest_treasuries', function ($table) {
          DB::statement('ALTER TABLE invest_treasuries ADD total DECIMAL(18,2) AS (price * quant)');
          DB::statement('ALTER TABLE invest_treasuries ADD total_orig DECIMAL(18,2) AS (price * quant_orig)');
          // DB::statement('ALTER TABLE invest_treasuries ADD duration_days int(18) AS (TIMESTAMPDIFF(day, date_invest,NOW()))');
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
        Schema::dropIfExists('invest_treasuries');
    }
}
