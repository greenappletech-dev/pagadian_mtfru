<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFvrApplicationChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fvr_application_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fvr_application_id')->constrained('fvr_applications')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('fvr_application_charges');
    }
}
