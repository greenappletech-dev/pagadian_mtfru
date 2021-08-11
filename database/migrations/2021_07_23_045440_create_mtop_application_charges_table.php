<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtopApplicationChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtop_application_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mtop_application_id')->constrained('mtop_applications')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('otherinc_id')->constrained('otherinc')->onUpdate('cascade')->onDelete('restrict');
            $table->double('price');
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
        Schema::dropIfExists('mtop_application_charges');
    }
}
