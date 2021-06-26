<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('employee_id')->nullable();
            $table->string('user_email');
            $table->string('name');
            $table->string('address');
            $table->string('lga');
            $table->string('state');
            $table->string('pincode');
            $table->string('mobile');
            $table->float('balance', 15,2)->default(0);
            $table->float('amount_paid', 15,2)->nullable();
            $table->string('coupon_code')->nullable();
            $table->float('coupon_amount')->default(0);
            $table->float('grand_total',15,2)->default(0);
            $table->string('order_status');
            $table->string('payment_method');
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
        Schema::dropIfExists('orders');
    }
}
