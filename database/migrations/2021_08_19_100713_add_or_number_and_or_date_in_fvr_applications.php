<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrNumberAndOrDateInFvrApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fvr_applications', function (Blueprint $table) {
            $table->string('or_number',20)->nullable();
            $table->date('or_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fvr_applications', function (Blueprint $table) {
            //
        });
    }
}
