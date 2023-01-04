<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdditionalDetailsInMtopAnnualTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtop_annual_taxes', function (Blueprint $table) {
            $table->foreignId('tricycle_id')->nullable()->constrained('tricycles')->onDelete('restrict')->onUpdate('cascade');
            $table->string('body_number', 45)->nullable();
            $table->string('make_type', 80)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('chassis_no', 80)->nullable();
            $table->string('plate_no', 80)->nullable();
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
