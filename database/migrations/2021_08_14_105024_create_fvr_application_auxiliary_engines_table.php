<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFvrApplicationAuxiliaryEnginesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fvr_application_auxiliary_engines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fvr_application_id')->constrained('fvr_applications')->onUpdate('cascade')->onDelete('restrict');
            $table->string('make_type', 45);
            $table->string('horsepower', 45);
            $table->string('engine_motor_no', 80);
            $table->string('cylinder', 45);
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
        Schema::dropIfExists('fvr_application_auxiliary_engines');
    }
}
