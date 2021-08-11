<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTricycleParameterToM99 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m99', function (Blueprint $table) {
            $table->string('mtfrb_case_no', 10)->nullable();
            $table->double('dropping_fee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m99', function (Blueprint $table) {
            //
        });
    }
}
