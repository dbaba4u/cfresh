<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();;
            $table->bigInteger('customer_id')->unsigned();;
            $table->string('sales_reps', 250)->nullable();
            $table->bigInteger('admin_employee_id')->unsigned()->default(0);
            $table->float('factor')->default(0);
            $table->date('expire_date');
            $table->smallInteger('status')->default(1);
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
//            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_settings');
    }
}
