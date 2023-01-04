<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtopAnnualTaxChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtop_annual_tax_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mtop_annual_tax_id')->constrained('mtop_annual_taxes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('otherinc_id')->constrained('otherinc')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('amount');
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
        Schema::dropIfExists('mtop_annual_tax_charges');
    }
}
