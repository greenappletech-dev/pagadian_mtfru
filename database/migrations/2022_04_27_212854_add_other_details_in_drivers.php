<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherDetailsInDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('address', 100)->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->string('gcash', 50)->nullable();
            $table->foreignId('tricycle_association_id')->nullable()->constrained('tricycle_associations')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            //
        });
    }
}
