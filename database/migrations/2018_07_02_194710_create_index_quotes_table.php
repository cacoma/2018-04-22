<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('index_id')->unsigned();
            $table->decimal('quote',18,4);
            $table->string('unit');
            $table->string('type');
            $table->dateTime('timestamp');
            $table->foreign('index_id')->references('id')->on('indices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_quotes');
    }
}
