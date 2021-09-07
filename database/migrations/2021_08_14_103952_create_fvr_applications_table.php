<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFvrApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fvr_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxpayer_id')->constrained('taxpayer')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('barangay_id')->constrained('barangay')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('banca_id')->constrained('bancas')->onUpdate('cascade')->onDelete('restrict');
            $table->date('transact_date');
            $table->string('name', 80);
            $table->string('color', 80);
            $table->double('length');
            $table->double('width');
            $table->double('dept');
            $table->double('gross_tonnage');
            $table->double('net_tonnage');
            $table->string('make_type', 45)->nullable();
            $table->string('horsepower', 45)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('cylinder', 45)->nullable();
            $table->foreignId('boat_type_id')->constrained('boat_types')->onUpdate('cascade')->onDelete('restrict');
            $table->string('fishing_gear')->nullable();
            $table->integer('manning_crew')->nullable();
            $table->string('body_number',80);
            $table->date('approve_date')->nullable();
            $table->date('validity_date')->nullable();
            $table->string('transact_type')->nullable();
            $table->integer('status');
            $table->string('user_id', 80)->nullable();
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
        Schema::dropIfExists('fvr_applications');
    }
}
