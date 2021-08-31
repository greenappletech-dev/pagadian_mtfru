<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('taxpayer')->onUpdate('cascade')->onDelete('restrict');
            $table->string('name', 80);
            $table->string('color', 80);
            $table->double('length');
            $table->double('width');
            $table->double('dept');
            $table->double('gross_tonnage')->nullable();
            $table->double('net_tonnage')->nullable();
            $table->string('make_type', 45)->nullable();
            $table->string('horsepower', 45)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('cylinder', 45)->nullable();
            $table->foreignId('boat_type_id')->constrained('boat_types')->onUpdate('cascade')->onDelete('restrict');
            $table->string('fishing_gear')->nullable();
            $table->integer('manning_crew')->nullable();
            $table->string('body_number',80);
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
        Schema::dropIfExists('bancas');
    }
}
