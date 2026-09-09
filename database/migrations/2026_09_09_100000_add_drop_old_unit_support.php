<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDropOldUnitSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* the password that releases an engine/chassis still held by an older
           tricycle. m99 already carries the other gate passwords of this database
           (bizapplupdatepwd, surchargepenaltywaivepassword), so it lives with them
           and the head can change it from the MTFRU Parameter page. */
        if(!Schema::hasColumn('m99', 'dropunitpassword')) {
            Schema::table('m99', function (Blueprint $table) {
                $table->string('dropunitpassword', 50)->nullable();
            });
        }

        Schema::table('tricycle_unit_histories', function (Blueprint $table) {

            /* who pressed Drop Old Unit. the password only proves the clerk was
               allowed to, it does not say which of them it was. */
            $table->unsignedBigInteger('dropped_by')->nullable()->after('mtop_application_id');

            /* set only on a manual Drop Old Unit. a row without it is an ordinary
               change unit recorded by updateTricycleDetails. */
            $table->timestamp('dropped_at')->nullable()->after('replaced_at');
        });

        /* start with no password rather than a guessable default: until the head
           sets one on the Parameter page, Drop Old Unit stays closed. */
        DB::table('m99')->where('par_code', '001')->update(['dropunitpassword' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tricycle_unit_histories', function (Blueprint $table) {
            $table->dropColumn(['dropped_by', 'dropped_at']);
        });

        if(Schema::hasColumn('m99', 'dropunitpassword')) {
            Schema::table('m99', function (Blueprint $table) {
                $table->dropColumn('dropunitpassword');
            });
        }
    }
}
