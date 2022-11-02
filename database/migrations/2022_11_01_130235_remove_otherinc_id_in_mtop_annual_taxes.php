<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOtherincIdInMtopAnnualTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtop_annual_taxes', function (Blueprint $table) {
            $table->dropForeign(['otherinc_id']);
            $table->dropColumn('otherinc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtop_annual_taxes', function (Blueprint $table) {
            //
        });
    }
}
