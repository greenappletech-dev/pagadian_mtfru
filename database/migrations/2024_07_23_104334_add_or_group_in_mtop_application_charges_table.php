<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrGroupInMtopApplicationChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtop_application_charges', function (Blueprint $table) {
            //
            
            $table->string('or_group',5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtop_application_charges', function (Blueprint $table) {
            //
            $table->dropColumn('or_group');
        });
    }
}
