<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MtopAnnualTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtop_annual_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('taxpayer')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('otherinc_id')->constrained('otherinc')->onUpdate('cascade')->onDelete('restrict');
            $table->string('name', 100);
            $table->string('address', 100)->nullable();
            $table->string('mobile_number', 45)->nullable();
            $table->date('transaction_date');
            $table->string('status', 1);
            $table->bigInteger('user_id');
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
        //
    }
}
