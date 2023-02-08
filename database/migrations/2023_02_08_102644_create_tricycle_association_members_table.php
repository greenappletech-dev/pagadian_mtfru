<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTricycleAssociationMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tricycle_association_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tricycle_association_id')->constrained('tricycle_associations')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('taxpayer_id')->constrained('taxpayer')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('tricycle_association_members');
    }
}
