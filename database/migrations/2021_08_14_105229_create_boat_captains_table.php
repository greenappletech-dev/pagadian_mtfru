<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatCaptainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_captains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banca_id')->constrained('bancas')->onUpdate('cascade')->onDelete('restrict');
            $table->string('last_name', 45);
            $table->string('first_name', 45);
            $table->string('middle_name', 45);
            $table->string('full_name', 80);
            $table->string('license_number', 40)->nullable();
            $table->string('image_location', 150)->nullable();
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
        Schema::dropIfExists('boat_captains');
    }
}
