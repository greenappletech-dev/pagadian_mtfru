<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtopApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtop_applications', function (Blueprint $table) {
            $table->id();
            $table->date('transact_date');
            $table->string('mtfrb_case_no', 30);
            $table->foreignId('taxpayer_id')->constrained('taxpayer')->onUpdate('cascade')->onDelete('restrict');
            $table->string('address', 200)->nullable();
            $table->foreignId('barangay_id')->constrained('barangay')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('tricycle_id')->constrained('tricycles')->onUpdate('cascade')->onDelete('restrict');
            $table->string('body_number', 20);
            $table->string('make_type', 80)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('chassis_no', 80)->nullable();
            $table->string('plate_no', 80)->nullable();
            $table->integer('status');
            $table->string('user_id', 80)->nullable();
            $table->date('approve_date')->nullable();
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
        Schema::dropIfExists('mtop_applications');
    }
}
