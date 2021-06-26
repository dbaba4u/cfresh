<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','employee_id','user_email','name','address','pincode','mobile','shipping_charges','coupon_code',
        'coupon_amount','order_status','payment_method','grand_total','state','country','balance','amount_paid'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * We calculate the column $total automatically every time
         * we call the $model->save();
         */
        self::saving(function($model){
            $model->balance = $model->grand_total - $model->amount_paid;
        });

    }

    /**
     * Override the Save function
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
//    public function save(array $options = [])
//    {
//        $this->balance = $this->grand_total - $this->amount_paid;
//        return parent::save();
//    }

    public function orders()
    {
        return $this->hasMany(OrdersProduct::class, 'order_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrderDetail($employee_id)
    {
        $getOrderDetail = Order::with('orders')->where('employee_id', $employee_id)->first();
        return $getOrderDetail;
    }
}
