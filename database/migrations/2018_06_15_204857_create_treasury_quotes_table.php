<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreasuryQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasury_quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('treasury_id')->unsigned();
            $table->decimal('rate',18,2);
            $table->decimal('min',18,2);
            $table->decimal('facevalue',18,2);
            $table->dateTime('timestamp');
            $table->timestamps();
          //foreign keys
          $table->foreign('treasury_id')->references('id')->on('treasuries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treasury_quotes');
    }
}
