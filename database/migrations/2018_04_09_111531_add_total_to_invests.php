<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddTotalToInvests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invests', function (Blueprint $table) {
            //Adicionar campo calculado automaticamente com SQL RAW - Machado 09/04/2018 14:12hs
            DB::statement('ALTER TABLE invests ADD total DECIMAL(18,4) AS (price * quant)');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invests', function (Blueprint $table) {
            //Remover campo calculado automaticamente com SQL RAW - Machado 09/04/2018 14:18hs
            $table->dropColumn('total');
        });
    }
}
