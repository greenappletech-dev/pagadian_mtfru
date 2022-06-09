<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtopAnnualTaxItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtop_annual_tax_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mtop_annual_tax_id')->constrained('mtop_annual_taxes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('tricycle_id')->constrained('tricycles')->onDelete('restrict')->onUpdate('cascade');
            $table->string('body_number', 45);
            $table->string('make_type', 80)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('chassis_no', 80)->nullable();
            $table->string('plate_no', 80)->nullable();
//            $table->integer('status');
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
        Schema::dropIfExists('mtop_annual_tax_items');
    }
}
