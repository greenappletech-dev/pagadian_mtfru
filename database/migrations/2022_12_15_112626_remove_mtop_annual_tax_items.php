<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMtopAnnualTaxItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtop_annual_tax_items', function (Blueprint $table) {
            Schema::dropIfExists('mtop_annual_tax_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtop_annual_tax_items', function (Blueprint $table) {
            //
        });
    }
}
