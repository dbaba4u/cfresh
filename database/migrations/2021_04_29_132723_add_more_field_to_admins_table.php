<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->tinyInteger('inventories_batch_access');
            $table->tinyInteger('payment_access');
            $table->tinyInteger('manage_queries_access');
            $table->tinyInteger('add_coupon_access');
            $table->tinyInteger('view_coupon_access');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('inventories_batch_access');
            $table->dropColumn('payment_access');
            $table->dropColumn('manage_queries_access');
            $table->dropColumn('add_coupon_access');
            $table->dropColumn('view_coupon_access');
        });
    }
}
