<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('securities', function (Blueprint $table) {
          $table->increments('id');
          // $table->string('symbol');
          $table->string('name');
          $table->string('type');
          $table->string('index');
          $table->string('ir');
          // $table->string('liquidity');
          $table->boolean('fgc');
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
        Schema::dropIfExists('securities');
    }
}
