<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTricycleUnitHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tricycle_unit_histories', function (Blueprint $table) {
            $table->id();

            /* the tricycle whose unit was replaced */
            $table->foreignId('tricycle_id')->constrained('tricycles')
                ->onUpdate('cascade')->onDelete('restrict');

            /* the application that caused the replacement (null for backfilled rows) */
            $table->foreignId('mtop_application_id')->nullable()->constrained('mtop_applications')
                ->onUpdate('cascade')->onDelete('set null');

            /* the operator holding the unit before it was replaced */
            $table->unsignedBigInteger('operator_id')->nullable();

            /* the unit details as they stood BEFORE the change unit / dropping */
            $table->string('body_number', 45)->nullable();
            $table->string('make_type', 80)->nullable();
            $table->string('engine_motor_no', 80)->nullable();
            $table->string('chassis_no', 80)->nullable();
            $table->string('plate_no', 80)->nullable();

            $table->timestamp('replaced_at')->nullable();
            $table->timestamps();

            $table->index('engine_motor_no');
            $table->index('chassis_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tricycle_unit_histories');
    }
}
