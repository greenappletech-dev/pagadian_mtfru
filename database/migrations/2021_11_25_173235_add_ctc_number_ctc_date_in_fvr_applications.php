<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCtcNumberCtcDateInFvrApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fvr_applications', function (Blueprint $table) {
            $table->string('ctc_no', 50)->nullable();
            $table->string('ctc_date', 20)->nullable();
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
