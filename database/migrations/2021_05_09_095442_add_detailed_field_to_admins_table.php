<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailedFieldToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->tinyInteger('customer_view_access');
            $table->tinyInteger('customer_view_order_access');
            $table->tinyInteger('customer_place_order_access');
            $table->tinyInteger('customer_add_access');

            $table->tinyInteger('employee_view_access');
            $table->tinyInteger('employee_add_access');
            $table->tinyInteger('employee_deactivated_access');
            $table->tinyInteger('employee_category_access');
            $table->tinyInteger('employee_pay_type_access');

            $table->tinyInteger('product_view_access');
            $table->tinyInteger('product_add_access');

            $table->tinyInteger('income_view_access');
            $table->tinyInteger('income_add_access');
            $table->tinyInteger('expenses_add_access');
            $table->tinyInteger('expenses_view_access');
            $table->tinyInteger('finance_check_balance_access');
            $table->tinyInteger('finance_cash_to_bank_access');
            $table->tinyInteger('finance_cash_from_bank_access');

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
            $table->dropColumn('customer_view_access');
            $table->dropColumn('customer_view_order_access');
            $table->dropColumn('customer_place_order_access');
            $table->dropColumn('customer_add_access');
            $table->dropColumn('employee_view_access');
            $table->dropColumn('employee_add_access');
            $table->dropColumn('employee_deactivated_access');
            $table->dropColumn('employee_category_access');
            $table->dropColumn('employee_pay_type_access');
            $table->dropColumn('product_view_access');
            $table->dropColumn('product_add_access');
            $table->dropColumn('income_view_access');
            $table->dropColumn('income_add_access');
            $table->dropColumn('expenses_add_access');
            $table->dropColumn('expenses_view_access');
            $table->dropColumn('finance_check_balance_access');
            $table->dropColumn('finance_cash_to_bank_access');
            $table->dropColumn('finance_cash_from_bank_access');
        });
    }
}
