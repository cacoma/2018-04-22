<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalculatedFieldsOperations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
      Schema::table('operations', function (Blueprint $table) {
            //Adicionar campo calculado automaticamente com SQL RAW - Machado 09/04/2018 14:12hs
            DB::statement('ALTER TABLE operations ADD diffReais DECIMAL(18,2) AS (total - total_orig)');
            DB::statement('ALTER TABLE operations ADD diffPerc DECIMAL(12,6) AS (((total / total_orig) - 1) * 100)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
