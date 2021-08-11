<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBodyNumberFromBodyNumberToForM99 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m99', function (Blueprint $table) {
            $table->string('body_number_from', 40)->nullable();
            $table->string('body_number_to', 40)->nullable();
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
