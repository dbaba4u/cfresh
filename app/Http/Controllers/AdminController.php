<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Batch;
use App\Batch_history;
use App\Box;
use App\Cap;
use App\CashFromBank;
use App\Damage;
use App\Employee;
use App\Expenses;
use App\Income;
use App\Label;
use App\Material;
use App\Order;
use App\OrdersProduct;
use App\Preform;
use App\Sale;
use App\TemAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $data = $request->input();
            $adminCount = Admin::where(['username'=>$data['username'], 'password'=>md5($data['password']),'status'=>1])->count();
//            dd($adminCount);
            if ($adminCount > 0 )
            {

                Session::put('adminSession', $data['username']);

                return redirect()->route('admin.dashboard')->with('flash_msg_success','You are Logged in!!');

            }else{
                return redirect()->route('admin.login')->with('flash_msg_error','Invalid Credentials, check your email or/and password and try again.');
            }
//            if (Auth::attempt(['username'=>$data['username'],'password'=>$data['password'], 'admin'=>1]))
//            {
        }

        return view('admin.admin_login');
    }

    public function dashboard()
    {
//        dd( Carbon::today()->format('Y-m-d'));
        if(Session::has('adminSession'))
        {
            /*Perform all dashboard task*/

        }else
        {
            return redirect()->route('admin.login')->with('flash_msg_error','Please login to access.');
        }

//        $created_at = Order::where('id',)

        $account = TemAccount::where('id',1)->first();
        $sales = Order::with('orders')->where('trans_date',Carbon::today()->format('Y-m-d'))->get();
        $products = Box::all();
        $materials = Material::all();
        $month = Carbon::now()->format('m/Y');
        $total_incomes = Income::where('inc_date',Carbon::today()->format('Y-m-d'))->sum('amount');
        $total_expense = Expenses::where('expense_date',Carbon::today()->format('Y-m-d'))->sum('amount');
        $preforms = Preform::where('no_bags','>',0)->get();
        $caps = Cap::where('no_bags','>',0)->get();
        $labels = Label::where('no_bags','>',0)->get();

//        $labels = json_decode(json_encode($labels));
//         echo '<pre>'; print_r($labels); die();
//         dd($total_income);
        $damages = !empty(Damage::where('ops_date',Carbon::today()->format('Y-m-d'))) ?
            Damage::where('ops_date',Carbon::today()->format('Y-m-d'))->sum('quantity')/36 : 0;
        $orders = Order::with('orders')->where('order_status','!=','Paid')
            ->where('trans_date',Carbon::today()->format('Y-m-d'))->get();
        /*==============================================================================================================*/
        //Todays Total Income Cash
        $income_total_cash_daily = empty(Income::where('cash_type','Cash')->whereDate('created_at', today()->toDateString())) ? 0 :
            Income::where('cash_type','Cash')->whereDate('created_at', today()->toDateString())->where('amount','>',0)->sum('amount');

        //Todays Total Income Wired
        $income_total_wired_daily = empty(Income::where('cash_type','Wired')->whereDate('created_at', today()->toDateString())) ? 0 :
            Income::where('cash_type','Wired')->whereDate('created_at', today()->toDateString())->where('amount','>',0)->sum('amount');

        //Total Income Cash
        $total_cash_income = empty(Income::where('cash_type','Cash')) ? 0 : Income::where('cash_type','Cash')->sum('amount');

        //Total Income Wired
        $total_wired_income= empty(Income::where('cash_type','Wired')) ? 0 : Income::where('cash_type','Wired')->sum('amount');

        //Todays Total Expense Cash
        $expense_total_cash_daily = empty(Expenses::where('cash_type','Cash')->whereDate('created_at', today()->toDateString())) ? 0 :
            Expenses::where('cash_type','Cash')->whereDate('created_at', today()->toDateString())->sum('amount');

        //Todays Total Expense Wired
        $expense_total_wired_daily = empty(Expenses::where('cash_type','Wired')->whereDate('created_at', today()->toDateString())) ? 0 :
            Expenses::where('cash_type','Wired')->whereDate('created_at', today()->toDateString())->sum('amount');

//        dd($expense_total_cash_daily);

        //Total Expense Cash
        $total_cash_expense = empty(Expenses::where('cash_type','Cash')) ? 0 : Expenses::where('cash_type','Cash')->sum('amount');

        //Total Expense Wired
        $total_wired_expense = empty(Expenses::where('cash_type','Wired')) ? 0 : Expenses::where('cash_type','Wired')->sum('amount');

        //Get the Cash from Bank
        $cash_from_bank = empty(CashFromBank::where('balance',">",0)) ? 0 : CashFromBank::where('balance',">",0)->sum('balance');

        //Get Sales Rep (i.e. Employee with Category id = 1)
        $sales_reps = Employee::where('category_id', 1)->where('deleted_at',null)->get();

        /*==============================================================================================================*/
        $income_amount = empty(Income::where('cash_type','cash')->where('inc_date',today()->toDateString())) ? 0 :
            Income::where('cash_type','cash')->where('inc_date',today()->toDateString())->sum('amount');
        $sales_amount = empty(Order::where('payment_method', 'Cash On Delivery')->where('trans_date',today()->toDateString())) ? 0 :
            Order::where('payment_method', 'Cash On Delivery')->where('trans_date',today()->toDateString())->sum('amount_paid');
        $total_income = $income_amount + $sales_amount;

        $expenses = empty(Expenses::where('cash_type','cash')->where('expense_date',today()->toDateString())) ? 0 :
            Expenses::where('cash_type','cash')->where('expense_date',today()->toDateString())->sum('amount');


        $cash_at_hand_daily = $total_income - $expenses;

        $income_amt = empty(Income::where('cash_type','cash')) ? 0 : Income::where('cash_type','cash')->sum('amount');
        $sales_amt = empty(Order::where('payment_method', 'Cash On Delivery')) ? 0 :
            Order::where('payment_method', 'Cash On Delivery')->sum('amount_paid');

        $total_inc = $income_amt + $sales_amt;

        $expense = empty(Expenses::where('cash_type','cash')) ? 0 :
            Expenses::where('cash_type','cash')->sum('amount');

        $cash_at_hand = $income_amt - $expense + $cash_from_bank;

//        dd($income_amt);

        $data['year_list'] = $this->fetch_year();

        return view('admin.admin_dashboard',
            compact('account', 'products', 'materials', 'sales','damages','cash_at_hand', 'income_total_cash_daily','income_total_wired_daily',
                'orders','preforms','caps','labels', 'total_cash_income', 'total_wired_income', 'expense_total_cash_daily',
                'expense_total_wired_daily','total_cash_expense','total_wired_expense', 'sales_reps'))->with($data);
    }

    public function fetch_year()
    {
        $data = DB::table('orders_products')->select(DB::raw('YEAR(created_at) year'))->groupBy('year')
            ->orderBy('year', 'DESC')->get();
        return $data;
    }

    public function fetch_data(Request $request)
    {
        if($request->input('year')){
            $chart_data = $this->fetch_chart_data($request->input('year'));
            $chart_data = json_decode(json_encode($chart_data));
            echo '<pre>'; print_r($chart_data); die();
            $arr = array();
            $arr_res = array();
            $count = count($chart_data);

            $this->objToArray($chart_data, $arr);
            $data = array();
            $prod = array();
            $output = array();

//            $arr = json_decode(json_encode($arr));
//            $arr = Arr::dot($arr);
//            dd();

//            echo '<pre>'; print_r($arr); die();
//            foreach ($arr as $values) {
//                if (!empty($values)){
//                    $data[] = $values;
//                }
//            }
//            echo json_encode($data[1]); exit();

//            echo '<pre>'; print_r($arr); die();
//            foreach ($arr as $keys => $values) {
//                if (!empty($values)){
//                    foreach ($arr[$keys] as $key => $value) {
//                        $arr_sales = array_column($value,'sales');
//                        $arr_month = array_column($value,'month');
//                        $arr_months = array();
//                        foreach ($arr_month as $month) {
//                            $arr_months[] = Carbon::parse(date("F", mktime(0, 0, 0, $month, 10)))
//                                ->format('M');
//                        }
//                        $product = $key;
//
//                        $output[] = array(
//                            'product' => $product,
//                            'month' => $arr_months,
//                            'sales' => $arr_sales,
//                        );
////                        echo '<pre>'; print_r($value); die();
//                    }
//                }
//            }
//            $data[] = $output;
//
            $data = json_decode(json_encode($data));

            echo json_encode($arr);
        }
    }

    public function fetch_chart_data($year)
    {
        $product_count = Box::all();
//        $data = [];
        foreach ($product_count as $pro) {
            $data[] = DB::table('orders_products')
                ->select(DB::raw('SUM(product_qty) as sales, product_id, MONTH(created_at) month'))->groupBy('product_id','month')
                ->where('product_id',$pro->id)->orderBy('month', 'ASC')
                ->whereYear('created_at','=', $year)->get();
//            ->groupBy('month');
        }
//        echo '<pre>'; print_r($data); exit();
        $results =Arr::collapse($data);
        $output=array();
        $month_arr=array();
        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $i = 1;
        foreach ( $results as $result) {
            $month_arr[]=$result->month;
            $month = Carbon::parse(date("F", mktime(0, 0, 0, $result->month, 10)))->format('M');
            $result->month = $month;
            $output[]=$result;

            $i++;
        }
//        echo '<pre>'; print_r($months[1]); die();
        return $output;

//        $data = DB::table('orders_products')
//            ->whereYear('created_at','=', $year)
//            ->get()
//            ->groupBy(function($val) {
//                return Carbon::parse($val->created_at)->format('Y');
//            });

    }

    public function objToArray($obj, &$arr){

        if(!is_object($obj) && !is_array($obj)){
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value)
        {
            if (!empty($value))
            {
                $arr[$key] = array();
                $this->objToArray($value, $arr[$key]);
            }
            else
            {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.login')->with('flash_msg_error','Logged out Successful.');
    }

//    public function showAdmins()
//    {
//        $admins = Admin::all();
//        $employees = Employee::all();
//        return view('admin.users.index', compact('admins','employees'));
//    }

//    public function editAdmin(Request $request, $id=null)
//    {
//        $user = Admin::findOrFail($id);
//
//        if ($request->isMethod('post'))
//        {
//            $this->validate($request, [
//                'employee_id'=>'required',
////                'username'=>'required|unique:admins'
//            ]);
//
////            dd($request->all());
//
//            if ($request->hasFile('image'))
//            {
//                $image = $request->image;
//                $new_image_name = time().$image->getClientOriginalName();
//                $image->move('images/backends_images/admins_images', $new_image_name);
//                $user->image = 'images/backends_images/admins_images/'.$new_image_name;
//            }
//
//            $user->employee_id=$request->employee_id;
//            $user->username=$request->username;
//
//            if ($request->has('password'))
//            {
//                $user->password = md5($request->password);
//            }
//
//            $user->save();
//
//            $notification = array(
//                'message' => 'User Account updated successfully!',
//                'alert-type' => 'success'
//            );
//
//            return redirect()->route('user.admin')->with($notification);
//
//        }
//        $employees = Employee::all();
//        return view('admin.users.edit', compact('user', 'employees'));
//    }

//    public function addAdmin(Request $request)
//    {
////        dd($request->all());
//        if ($request->isMethod('post'))
//        {
//            $this->validate($request, [
//                'employee_id'=>'required|unique:admins',
//                'username'=>'required|unique:admins'
//            ]);
//
//            $data = $request->all();
//
//            $user = new Admin();
//            $user->username = $data['username'];
//            $user->password = md5('password');
//            $user->employee_id = $data['employee_id'];
//
//            $user->save();
//
////            Admin::create([
////                'employee_id'=>$request->employee_id,
////                'username'=>$request->username,
////                'password'=>md5('password')
////            ]);
//
//            $notification = array(
//                'message' => 'User created successfully!',
//                'alert-type' => 'success'
//            );
//
//            return redirect()->route('user.admin')->with($notification);
//        }
//
//    }

    public function is_admin($id)
    {
        $user = Admin::findOrFail($id);
        $user->status = 0;

        $user->save();

        $notification = array(
            'message' => 'User permissions changed.',
            'alert-type' => 'success'
        );

        return redirect()->route('user.admin')->with($notification);
    }

    public function not_admin($id)
    {
        $user = Admin::findOrFail($id);
        $user->status = 1;

        $user->save();

        $notification = array(
            'message' => 'User permissions changed.',
            'alert-type' => 'success'
        );

        return redirect()->route('user.admin')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        $user->delete();

        $notification = array(
            'message' => 'User account deactivated',
            'alert-type' => 'warning'
        );

        return redirect()->route('viewAdmins')->with($notification);
    }

    public function trashes()
    {
        $users = Admin::onlyTrashed()->get();
//        dd($users);
        return view('admin.users.deactivated', compact('users'));
    }

    public function restore($id)
    {
        $user = Admin::where('id',$id)->onlyTrashed()->first();

        $user->restore();

        $notification = array(
            'message' => 'User account re-activated!!',
            'alert-type' => 'success'
        );

        return redirect()->route('user.admin')->with($notification);
    }

    public function delete($id)
    {
        $user = Admin::where('id',$id)->onlyTrashed()->first();

        $user->forceDelete();

        $notification = array(
            'message' => 'User account is permanently deleted.',
            'alert-type' => 'error'
        );

        return redirect()->route('user.admin')->with($notification);
    }

    public function viewAdmins()
    {
        $admins = Admin::all();
//        $admins = json_decode(json_encode($admins));
//        echo '<pre>'; print_r($admins); die();
        return view('admin.admins.view_admins', compact('admins'));
    }

    public function addAdmin(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $data = $request->all();
//            dd($data);
            $adminCount = Admin::where('username', $data['username'])->count();
            if ($adminCount>0)
            {
                $notification = array(
                    'message' => 'Admin Username already exists, Please choose another.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }else
            {
                if ($data['type']=='Admin')
                {
                    $admin = new Admin();
                    $admin->type = $data['type'];
                    $admin->employee_id = $data['employee_id'];
                    $admin->username = $data['username'];
                    $admin->password = md5($data['password']);
                    $admin->status = empty($data['status']) ? 0 : $data['status'];

                    /*$admin->employees_access = 3;
                    $admin->products_access = 3;
                    $admin->finance_access = 3;
                    $admin->orders_access = 3;*/

                    $admin->customer_view_access = 1;
                    $admin->customer_view_order_access = 1;
                    $admin->customer_place_order_access = 1;
                    $admin->customer_add_access = 1;

                    $admin->employee_view_access = 1;
                    $admin->employee_add_access = 1;
                    $admin->employee_deactivated_access = 1;
                    $admin->employee_category_access = 1;
                    $admin->employee_pay_type_access = 1;

                    $admin->product_view_access = 1;
                    $admin->product_add_access = 1;

                    $admin->income_view_access = 1;
                    $admin->income_add_access = 1;
                    $admin->expenses_add_access = 1;
                    $admin->expenses_view_access = 1;
                    $admin->finance_check_balance_access = 1;
                    $admin->finance_cash_to_bank_access = 1;
                    $admin->finance_cash_from_bank_access = 1;

                    $admin->users_access = 3;
                    $admin->inventories_view_access = 1;
                    $admin->inventories_manage_access = 1;
                    $admin->inventories_batch_access = 1;
                    $admin->store_move_access = 1;
                    $admin->store_view_access = 1;
                    $admin->operation_access = 1;

                    $admin->payment_access = 1;
                    $admin->manage_queries_access = 1;

                    $admin->view_coupon_access = 1;
                    $admin->add_coupon_access = 1;

                    $admin->save();
                    $notification = array(
                        'message' => 'Admin User added successfully!!',
                        'alert-type' => 'success'
                    );
                    return redirect()->back()->with($notification);

                }else  if ($data['type']=='Sub Admin')
                {
                    $admin = new Admin();
                    $admin->type = $data['type'];
                    $admin->employee_id = $data['employee_id'];
                    $admin->username = $data['username'];
                    $admin->password = md5($data['password']);
                    $admin->status = empty($data['status']) ? 0 : $data['status'];
                 /*   $admin->employees_access = empty($data['employees_access']) ? 0 : $data['employees_access'];
                    $admin->products_access = empty($data['products_access']) ? 0 : $data['products_access'];
                    $admin->finance_access = empty($data['finance_access']) ? 0 : $data['finance_access'];*/

                    $admin->customer_view_access = empty($data['customer_view_access']) ? 0 : $data['customer_view_access'];
                    $admin->customer_view_order_access = empty($data['customer_view_order_access']) ? 0 : $data['customer_view_order_access'];
                    $admin->customer_place_order_access = empty($data['customer_place_order_access']) ? 0 : $data['customer_place_order_access'];
                    $admin->customer_add_access = empty($data['customer_add_access']) ? 0 : $data['customer_add_access'];

                    $admin->employee_view_access = empty($data['employee_view_access']) ? 0 : $data['employee_view_access'];
                    $admin->employee_add_access = empty($data['employee_add_access']) ? 0 : $data['employee_add_access'];
                    $admin->employee_deactivated_access = empty($data['employee_deactivated_access']) ? 0 : $data['employee_deactivated_access'];
                    $admin->employee_category_access = empty($data['employee_category_access']) ? 0 : $data['employee_category_access'];
                    $admin->employee_pay_type_access = empty($data['employee_pay_type_access']) ? 0 : $data['employee_pay_type_access'];

                    $admin->product_view_access = empty($data['product_view_access']) ? 0 : $data['product_view_access'];
                    $admin->product_add_access = empty($data['product_add_access']) ? 0 : $data['product_add_access'];

                    $admin->income_view_access = empty($data['income_view_access']) ? 0 : $data['income_view_access'];
                    $admin->income_add_access = empty($data['income_add_access']) ? 0 : $data['income_add_access'];
                    $admin->expenses_add_access = empty($data['expenses_add_access']) ? 0 : $data['expenses_add_access'];
                    $admin->expenses_view_access = empty($data['expenses_view_access']) ? 0 : $data['expenses_view_access'];
                    $admin->finance_check_balance_access = empty($data['finance_check_balance_access']) ? 0 : $data['finance_check_balance_access'];
                    $admin->finance_cash_to_bank_access = empty($data['finance_cash_to_bank_access']) ? 0 : $data['finance_cash_to_bank_access'];
                    $admin->finance_cash_from_bank_access = empty($data['finance_cash_from_bank_access']) ? 0 : $data['finance_cash_from_bank_access'];

                    $admin->users_access = empty($data['users_access']) ? 0 : $data['users_access'];
                    $admin->inventories_view_access = empty($data['inventories_view_access']) ? 0 : $data['inventories_view_access'];
                    $admin->inventories_manage_access = empty($data['inventories_manage_access']) ? 0 : $data['inventories_manage_access'];
                    $admin->store_move_access = empty($data['store_move_access']) ? 0 : $data['store_move_access'];
                    $admin->store_view_access = empty($data['store_view_access']) ? 0 : $data['store_view_access'];
                    $admin->operation_access = empty($data['damage_operation_access']) ? 0 : $data['damage_operation_access'];

                    $admin->inventories_batch_access = empty($data['inventories_batch_access']) ? 0 : $data['inventories_batch_access'];
                    $admin->manage_queries_access = empty($data['manage_queries_access']) ? 0 : $data['manage_queries_access'];
                    $admin->payment_access = empty($data['payment_access']) ? 0 : $data['payment_access'];
                    $admin->add_coupon_access = empty($data['add_coupon_access']) ? 0 : $data['add_coupon_access'];
                    $admin->view_coupon_access = empty($data['view_coupon_access']) ? 0 : $data['view_coupon_access'];

                    $admin->save();
                    $notification = array(
                        'message' => 'Sub Admin User added successfully!!',
                        'alert-type' => 'success'
                    );
                    return redirect()->back()->with($notification);
                }

            }

        }
        $employees = Employee::all();
        return view('admin.admins.add_admin', compact('employees'));
    }

    public function editAdmin(Request $request, $id=null)
    {

        if ($request->isMethod('post'))
        {
            $data = $request->all();
//            dd($data);
            $status = empty($data['status']) ? 0 : $data['status'];
           /* $employees_access = empty($data['employees_access']) ? 0 : $data['employees_access'];
            $products_access = empty($data['products_access']) ? 0 : $data['products_access'];
            $orders_access = empty($data['orders_access']) ? 0 : $data['orders_access'];*/
            $users_access = empty($data['users_access']) ? 0 : $data['users_access'];
            /*$finance_access = empty($data['finance_access']) ? 0 : $data['finance_access'];*/

            $customer_view_access = empty($data['customer_view_access']) ? 0 : $data['customer_view_access'];
            $customer_view_order_access = empty($data['customer_view_order_access']) ? 0 : $data['customer_view_order_access'];
            $customer_place_order_access = empty($data['customer_place_order_access']) ? 0 : $data['customer_place_order_access'];
            $customer_add_access = empty($data['customer_add_access']) ? 0 : $data['customer_add_access'];

            $employee_view_access = empty($data['employee_view_access']) ? 0 : $data['employee_view_access'];
            $employee_add_access = empty($data['employee_add_access']) ? 0 : $data['employee_add_access'];
            $employee_deactivated_access = empty($data['employee_deactivated_access']) ? 0 : $data['employee_deactivated_access'];
            $employee_category_access = empty($data['employee_category_access']) ? 0 : $data['employee_category_access'];
            $employee_pay_type_access = empty($data['employee_pay_type_access']) ? 0 : $data['employee_pay_type_access'];

            $product_view_access = empty($data['product_view_access']) ? 0 : $data['product_view_access'];
            $product_add_access = empty($data['product_add_access']) ? 0 : $data['product_add_access'];

            $income_view_access = empty($data['income_view_access']) ? 0 : $data['income_view_access'];
            $income_add_access = empty($data['income_add_access']) ? 0 : $data['income_add_access'];
            $expenses_add_access = empty($data['expenses_add_access']) ? 0 : $data['expenses_add_access'];
            $expenses_view_access = empty($data['expenses_view_access']) ? 0 : $data['expenses_view_access'];
            $finance_check_balance_access = empty($data['finance_check_balance_access']) ? 0 : $data['finance_check_balance_access'];
            $finance_cash_to_bank_access = empty($data['finance_cash_to_bank_access']) ? 0 : $data['finance_cash_to_bank_access'];
            $finance_cash_from_bank_access = empty($data['finance_cash_from_bank_access']) ? 0 : $data['finance_cash_from_bank_access'];

            $inventories_view_access = empty($data['inventories_view_access']) ? 0 : $data['inventories_view_access'];
            $inventories_manage_access = empty($data['inventories_manage_access']) ? 0 : $data['inventories_manage_access'];
            $store_move_access = empty($data['store_move_access']) ? 0 : $data['store_move_access'];
            $store_view_access = empty($data['store_view_access']) ? 0 : $data['store_view_access'];
            $operation_access = empty($data['damage_operation_access']) ? 0 : $data['damage_operation_access'];

            $inventories_batch_access = empty($data['inventories_batch_access']) ? 0 : $data['inventories_batch_access'];
            $manage_queries_access = empty($data['manage_queries_access']) ? 0 : $data['manage_queries_access'];
            $payment_access = empty($data['payment_access']) ? 0 : $data['payment_access'];
            $add_coupon_access = empty($data['add_coupon_access']) ? 0 : $data['add_coupon_access'];
            $view_coupon_access = empty($data['view_coupon_access']) ? 0 : $data['view_coupon_access'];


            $old_password = Admin::where('username',$data['username'])->first()->password;
            $password =!empty( $data['password']) ?  md5($data['password']) : $old_password;

//            dd($data);

            if ($data['type']=='Admin')
            {
                Admin::where('username',$data['username'])->update(['password'=>$password, 'status'=>$status]);
                $notification = array(
                    'message' => 'Admin User Updated successfully!!',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);

            }else  if ($data['type']=='Sub Admin')
            {
                Admin::where('username',$data['username'])->update(['password'=>$password, 'status'=>$status,
                    'inventories_view_access'=>$inventories_view_access,'inventories_manage_access'=>$inventories_manage_access,
                    'store_move_access'=>$store_move_access, 'store_view_access'=>$store_view_access, 'customer_view_access'=>$customer_view_access,

                    'customer_view_order_access'=>$customer_view_order_access,'customer_place_order_access'=>$customer_place_order_access, 'users_access'=>$users_access,
                    'employee_view_access'=>$employee_view_access,'employee_add_access'=>$employee_add_access, 'employee_deactivated_access'=>$employee_deactivated_access,
                    'employee_category_access'=>$employee_category_access,'employee_pay_type_access'=>$employee_pay_type_access, 'product_view_access'=>$product_view_access,
                    'product_add_access'=>$product_add_access,'income_view_access'=>$income_view_access, 'income_add_access'=>$income_add_access,
                    'expenses_add_access'=>$expenses_add_access,'expenses_view_access'=>$expenses_view_access, 'finance_check_balance_access'=>$finance_check_balance_access,
                    'finance_cash_to_bank_access'=>$finance_cash_to_bank_access,'finance_cash_from_bank_access'=>$finance_cash_from_bank_access,
                    'customer_add_access'=>$customer_add_access, 'operation_access'=>$operation_access, 'inventories_batch_access'=>$inventories_batch_access,
                    'manage_queries_access'=>$manage_queries_access, 'payment_access'=>$payment_access,
                    'add_coupon_access'=>$add_coupon_access, 'view_coupon_access'=>$view_coupon_access
                    ]);

                $notification = array(
                    'message' => 'Sub Admin User updated successfully!!',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);
            }
        }
        $admin = Admin::where('id',$id)->first();
//        dd($admin);
        return view('admin.admins.edit_admin', compact('admin'));
    }

    public function orderChart()
    {
//        $current_year_sales = OrdersProduct::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
//        $yearly_orders = OrdersProduct::whereYear('created_at', Carbon::now()->year)->get();
        $data = DB::table('orders_products')
            ->select(DB::raw('MONTH(created_at) label, SUM(product_qty) as y,  YEAR(created_at) year, product_id'))->groupBy('year','label','product_id')
//            ->where('product_id',$pro->id)
            ->orderBy('label', 'ASC')
            ->whereYear('created_at','=', 2019)->get()
            ->groupBy('label');

        $data = json_decode(json_encode($data));
        $this->objToArray($data, $arrs);
        foreach ($arrs as $key=>$arr) {
            Arr::pull($arr[0], 'year');
            $months = Arr::get($arr[0], 'label');
            $month = Carbon::parse(date("F", mktime(0, 0, 0, $months, 10)))->format('M');
            Arr::set($arr[0], 'label', $month);
            $sales_arr[] = $arr[0];
        }
        Arr::pull($sales_arr, 'year');

        $prods = Box::all();

        $dataset = array();
        $prod_name = array();
        foreach ($prods as $prod) {
            $prod_type = array();
            foreach ($sales_arr as $key => $value) {
                if ($value['product_id'] == $prod->id) {
                    $prod_type[]=$value;
                }
            }
            $prod_name[]=$prod->case;
            $dataset[] =$prod_type;
        }

//        for ($i = 1; $i<=count($prods); $i++) {
//            foreach ($sales_arr as $key => $value) {
//                if ($value['product_id'] == 'Corporate') {
//                    print_r($value['trx']);
//                }
//                echo "<br/>";
//            }
//        }

//        $scores = $testScores->mapToDictionary(function ($item, $key) {
//            return [$item['name'] => $item['score']];
//        });

//        echo '<pre>'; print_r(strtotime('Jan')); die();
        return view('admin.orders.view_orders_charts')->with(compact('sales_arr', 'dataset','prod_name'));
    }


}
