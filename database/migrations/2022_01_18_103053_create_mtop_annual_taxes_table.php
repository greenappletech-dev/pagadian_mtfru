<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtopAnnualTaxesTable extends Migration
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
            $table->foreignId('tricycle_id')->constrained('tricycles')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('barangay_id')->constrained('barangay')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('otherinc_id')->constrained('otherinc')->onUpdate('cascade')->onDelete('restrict');
            $table->string('body_number', 20);
            $table->string('make_type', 80)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('chassis_no', 80)->nullable();
            $table->string('plate_no', 80)->nullable();
            $table->date('transaction_date');
            $table->string('status', 1);
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
        Schema::dropIfExists('mtop_annual_taxes');
    }
}
