<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatories', function (Blueprint $table) {
            $table->id();
            $table->string('ac_officer')->nullable();
            $table->string('ac_verified')->nullable();
            $table->string('ac_noted')->nullable();
            $table->string('ac_approved')->nullable();
            $table->string('cn_recommending')->nullable();
            $table->string('cn_noted')->nullable();
            $table->string('cn_approved')->nullable();
            $table->string('sc_inspected')->nullable();
            $table->string('sc_noted')->nullable();
            $table->string('sc_approved')->nullable();
            $table->string('mo_recommending')->nullable();
            $table->string('mo_approved')->nullable();
            $table->string('bc_recommending')->nullable();
            $table->string('bc_approved')->nullable();
            $table->string('cf_recommending')->nullable();
            $table->string('cf_approved')->nullable();
            $table->string('sf_verified')->nullable();
            $table->string('sf_recommending')->nullable();
            $table->string('sf_approved')->nullable();
            $table->string('pb_certified')->nullable();
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
        Schema::dropIfExists('signatories');
    }
}
