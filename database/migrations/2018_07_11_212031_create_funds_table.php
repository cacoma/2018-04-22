<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('cnpj', 18);
            $table->dateTime('reg_date');
            $table->dateTime('const_date');
            $table->dateTime('canc_date')->nullable();
            $table->string('sit');
            $table->string('classe');
            $table->string('rentabilidade')->nullable();
            $table->boolean('inv_qual')->nullable();
            $table->boolean('fundo_exc')->nullable();
            $table->boolean('fundo_cotas')->nullable();
            $table->boolean('ir')->nullable();
            $table->string('taxa_perf')->nullable();
            $table->string('diretor');
            $table->string('admin');
            $table->string('gestor');
            $table->string('auditor');
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
        Schema::dropIfExists('funds');
    }
}
