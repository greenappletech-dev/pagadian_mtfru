<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTricyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tricycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('taxpayer')->onUpdate('cascade')->onDelete('restrict');
            $table->string('body_number', 45);
            $table->string('make_type', 45)->nullable();
            $table->string('engine_motor_no', 45)->nullable();
            $table->string('chassis_no', 45)->nullable();
            $table->string('plate_no', 45)->nullable();
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
        Schema::dropIfExists('tricycles');
    }
}
