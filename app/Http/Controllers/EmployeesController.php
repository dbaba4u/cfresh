<?php

namespace App\Http\Controllers;

use App\Account;
use App\Bank;
use App\Category;
use App\CommissionSetting;
use App\Customer;
use App\Employee;
use App\Order;
use App\OrdersProduct;
use App\Payment;
use App\Profile;
use App\Query;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use PDF;
use Session;
use App\Admin;

class EmployeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $employees = Employee::all();

        return view('admin.employees.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $employees = Employee::all();

        $categories = Category::all();

        $banks = Bank::all();

        return view('admin.employees.create',compact('employees','categories','banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:employees',
            'phone'=>'required',
            'joined'=>'required',
            'category_id'=>'required',
            'address'=>'required',
            'avatar'=>'required|image'
        ]);

        $payment_id = Category::findOrFail($request->category_id)->payment->id;
        $employee = Employee::create([
            'name'=>$request->name,
            'category_id'=>$request->category_id,
            'account_no'=>$request->account_no,
            'account_name'=>$request->account_name,
            'bank_id'=>$request->bank_id,
            'target'=>$request->target,
            'payment_id'=>$payment_id,
            'joined'=>Carbon::parse($request->joined)->format('Y-m-d H:i:s'),
        ]);

        $avatar = '';

        if ($request->hasFile('avatar'))
        {
            $image = $request->avatar;
            $new_image_name = time().$image->getClientOriginalName();
            $image->move('uploads/employees/images', $new_image_name);
            $avatar = 'uploads/employees/images/'.$new_image_name;
        }

        $profile = Profile::create([
            'employee_id'=>$employee->id,
            'avatar'=>$avatar,
            'address'=>$request->address,
            'phone'=>$request->phone
        ]);
//        $employee->customers()->attach($request->customers());

        $notification = array(
            'message' => 'Employee created successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('employees')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit_sales(Request $request, $id)
    {
        $employee = Employee::where('id',$id)->first();
        $categories =Category::all();
        $banks = Bank::all();
        $orders = Order::with('orders')->where('employee_id', $id)->get();

        $target =(($employee->target)*(Carbon::now()->day))/Carbon::now()->daysInMonth;

        $accounts = Account::where('employee_id',$id)->get();

        if ($request->isMethod('post'))
        {
            $from = Carbon::parse($request->input('from') . ' 01:00:00')->toDateTimeString();
            $to = Carbon::parse($request->input('to') . ' 23:59:00')->toDateTimeString();
            if ($request->has('search'))
            {
                // select search
//                dd($from);
                $search =  Order::with('orders')->where('employee_id', $id)->whereBetween('created_at', [$from, $to])->get();
                return view('admin.employees.edit',['orders' => $search, 'employee'=>$employee, 'categories' => $categories,
                    'banks'=>$banks, 'target'=>$target, 'accounts'=>$accounts]);
            }elseif ($request->has('exportPDF'))
            {
                // select PDF
                $PDFReport = Order::with('orders')->with('employee')->where('employee_id', $id)->whereBetween('created_at', [$from, $to])->get();
                $orders = $PDFReport;
                $total[] = array();
                $PDFReport = json_decode(json_encode($PDFReport));

                foreach ($PDFReport as $order) {
                    foreach ($order->orders as $order1) {
                        $total[] = $order1->product_qty;
                    }
                }
                 $total_quantity = array_sum($total);
                $pdf = PDF::loadView('admin.employees.edit_pdfview', ['PDFReport' => $orders, 'from'=>$from, 'to'=>$to,
                    'total_quantity'=>$total_quantity, 'employee'=>$employee, 'categories' => $categories,
                    'banks'=>$banks, 'target'=>$target, 'accounts'=>$accounts])->setPaper('a4', 'landscape');
                return $pdf->download('sales-report.pdf');
            }
        }
        else
        {
            $employees = Employee::where('category_id',1)->get();
            $customers = User::where('status',1)->where('admin',0)->get();
            $commission_settings = CommissionSetting::where('employee_id', $id)->with('customer')->get();
            $queries = Query::where('employee_id',$id)->get();
            return view('admin.employees.edit',compact('employee','commission_settings','customers', 'employees',
                'categories','banks','orders','target','accounts', 'queries'));
        }


    }

    public function edit_commission(Request $request, $id)
    {
        $admin_employee_id = Admin::with('employee')->where('username',Session::get('adminSession'))->first();
//        echo (is_int((int)$admin_employee_id->id)); exit();
        if ($request->isMethod('post'))
        {
            $status = 1;
            if ($request->isMethod('post') ) {
                $data = $request->all();
                $customer_id = $data['customer_id'];
                $employee_id = $data['curr_employee'];
                $expire_date = $data['expire_date'];
                $sfactor = !empty($data['sfactor']) ? $data['sfactor'] : 0;

                /*Geting Array from the form*/
                $sales_rep = array();

                $ar_employee_id =!empty($data['employee_id']) ? $data['employee_id'] : 0;
                $ar_factor =!empty($data['factor']) ? $data['factor'] : 0;
                $commSettings = CommissionSetting::where('employee_id', $id)->where('customer_id', $customer_id)->count();

                if ($commSettings > 0){
                    $notification = array(
                        'message' => 'This Customer has already been added.',
                        'alert-type' => 'warning'
                    );

                    return redirect()->back()->with($notification);
                }

                $factor_arr = array();
                if ($sfactor == 0) {
                    if (!empty($data['employee_id']))
                    {
                        $ar_employee_id = $data['employee_id'];
                        foreach ($ar_employee_id as $key=>$value) {
                            $sales_rep[] =  $ar_employee_id[$key];
                        }
                        $string='';
                        foreach ($sales_rep as $value){
                            $string .=  $value.'->';
                        }
                        $rep = substr($string,0,-2);
                        if (!empty($rep))
                        {
                            CommissionSetting::create([
                                'employee_id'=>$employee_id,
                                'sales_reps'=>$rep,
                                'customer_id'=>$customer_id,
                                'expire_date'=>$expire_date,
                                'admin_id'=>$admin_employee_id
                            ]);
                        }
                    }

                }else
                {
                    CommissionSetting::create([
                        'employee_id'=>$employee_id,
                        'sales_reps'=>$employee_id,
                        'customer_id'=>$customer_id,
                        'factor'=>$sfactor,
                        'expire_date'=>$expire_date,
                        'admin_id'=>$admin_employee_id
                    ]);

                }
                $notification = array(
                    'message' => 'Commission Setting created successfully',
                    'alert-type' => 'success'
                );

                return redirect()->back()->with($notification);
            }
        }

//select all
        $employees = Employee::where('category_id',1)->get();
        $customers = User::where('status',1)->where('admin',0)->get();
        $commission_settings = CommissionSetting::where('employee_id', $id)->get();
        foreach ($commission_settings as $setting) {
            //if Factor is Expire
            if (today() > $setting->expire_date)
            {
                CommissionSetting::where('employee_id', $id)->update(['status'=>0, 'comment'=>'Expired']);
            }
        }
        return view('admin.employees.edit',compact('customers', 'employees','commission_settings'));
    }

    public function deleteCommissionSetting($id)
    {
        CommissionSetting::where('id', $id)->delete();
        $notification = array(
            'message' => 'Commission Setting deleted successfully!',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    public function edit_account(Request $request, $id)
    {
        $employee = Employee::where('id',$id)->first();
        $balance = $employee->balance;
        $categories =Category::all();
        $banks = Bank::all();
        $orders = Order::with('orders')->where('employee_id', $id)->get();

        $target =(($employee->target)*(Carbon::now()->day))/Carbon::now()->daysInMonth;

        $accounts = Account::where('employee_id',$id)->get();

        if ($request->isMethod('post'))
        {
            $from = Carbon::parse($request->input('from') . ' 01:00:00')->toDateTimeString();
            $to = Carbon::parse($request->input('to') . ' 23:59:00')->toDateTimeString();
            if ($request->has('search'))
            {
                // select search
//                dd($from);
                $search =  Account::with('employee')->where('employee_id', $id)->whereBetween('created_at', [$from, $to])->get();
                return view('admin.employees.edit',['orders' => $orders, 'employee'=>$employee, 'categories' => $categories,
                    'banks'=>$banks, 'target'=>$target, 'balance'=>$balance, 'accounts'=>$search]);
            }elseif ($request->has('exportPDF'))
            {
                // select PDF
                $PDFReport = Account::with('admin')->where('employee_id', $id)->whereBetween('created_at', [$from, $to])->get();
                $PDFReport = json_decode(json_encode($PDFReport));
                $total_amount = array_sum(array_column($PDFReport, 'amount'));

//                foreach ($PDFReport as $order) {
//                    dd($order->orders->sum('product_qty'));
//                }
//                $total_quantity = $PDFReport[0]->orders->sum( 'product_qty');

                $pdf = PDF::loadView('admin.employees.edit_account_pdfview', ['PDFReport' => $PDFReport, 'from'=>$from, 'to'=>$to,
                    'total_amount'=>$total_amount, 'employee'=>$employee, 'categories' => $categories,
                    'banks'=>$banks, 'target'=>$target, 'balance'=>$balance, 'orders'=>$orders])->setPaper('a4', 'landscape');
                return $pdf->download('account-report.pdf');
            }
        }
        else
        {
            //select all
            $employees = Employee::where('category_id',1)->get();
            $customers = User::where('status',1)->where('admin',0)->get();
            $commission_settings = CommissionSetting::where('employee_id', $id)->with('customer')->get();
            $queries = Query::where('employee_id',$id)->get();
            return view('admin.employees.edit',compact('employee','commission_settings','balance','customers',
                'employees', 'categories','banks','orders','target','accounts', 'queries'));
//            return view('admin.employees.edit',compact('employee', 'balance', 'categories','banks','orders','target','accounts'));
        }


    }

    public function display_query(Request $request, $id)
    {
        $employee = Employee::where('id',$id)->first();
        $balance = $employee->balance;
        $categories =Category::all();
        $banks = Bank::all();
        $orders = Order::with('orders')->where('employee_id', $id)->get();

        $target =(($employee->target)*(Carbon::now()->day))/Carbon::now()->daysInMonth;

        $accounts = Account::where('employee_id',$id)->get();

            //select all
        $employees = Employee::where('category_id',1)->get();
        $customers = User::where('status',1)->where('admin',0)->get();
        $commission_settings = CommissionSetting::where('employee_id', $id)->with('customer')->get();

        $queries = Query::where('employee_id',$id)->get();


        return view('admin.employees.edit',compact('employee','commission_settings','balance','customers',
            'employees', 'categories','banks','orders','target','accounts', 'queries'));
//            return view('admin.employees.edit',compact('employee', 'balance', 'categories','banks','orders','target','accounts'));


    }

    public function addFactor(Request $request, $id)
    {
        $factor = $request->factor;
        CommissionSetting::where('id', $id)->update(['factor' =>$factor]);

        $notification = array(
            'message' => 'Commission Factor Updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function addNewRow(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $data = $request->all();
            $customers = User::where('status',1)->where('admin',0)->get();
            $employees = Employee::where('category_id',1)->get();
            if (isset($data['getNewOrderItem']))
            {
                ?>
                <tr>
                    <td hidden class="number">1</td>
                    <td>
                        <div class="form-group row " >
                            <label for="amount" class="col-sm-4 col-form-label text-right">Sales Rep.</label>
                            <div class="col-sm-8" style="margin-top: 0.3rem">
                                <select name="employee_id[]"  class="form-control form-control-sm select2" required>
                            <option value="">Select Sales Rep. </option>
                         <?php  foreach($employees as $employee){   ?>
                            <option value=" <?php echo $employee->name ?>"><?php echo $employee->name ?></option>
                           <?php  } ?>
                        </select>
                            </div>

                        </div>
                    </td>

                    <td style="padding-left: 2rem;">
                        <button class="btn btn-danger text-center btn-sm remove_rep text-center" data-toggle="tooltip" data-bs-tooltip="" id="remove-rep" type="button" name="remove-rep" title="Remove the selected Sales Rep." >-</button>
                    </td>
                </tr>
                <?php

                exit();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required',
            'phone'=>'required',
            'joined'=>'required',
            'category_id'=>'required',
            'address'=>'required'

        ]);

        $employee = Employee::findOrFail($id);
        $profile = Profile::where('employee_id',$id)->first();


        $payment_id = Category::findOrFail($request->category_id)->payment->id;

        if ($request->hasFile('avatar'))
        {
            $image = $request->avatar;
            $new_image_name = time().$image->getClientOriginalName();
            $image->move('uploads/employees/images', $new_image_name);
            $profile->avatar = 'uploads/employees/images/'.$new_image_name;
        }

        $address = $request->address;

        $employee->name=$request->name;
        $employee->category_id=$request->category_id;
        $employee->account_no=$request->account_no;
        $employee->account_name=$request->account_name;
        $employee->bank_id=$request->bank_id;
        $employee->joined=$request->joined;
        $employee->target=$request->target;
        $employee->payment_id=$payment_id;

        $profile->employee_id=$id;
        $profile->address=$address;
        $profile->phone=$request->phone;

        $employee->save();

        $profile->save();

        $notification = array(
            'message' => 'Employee details updated successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('employees')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        Profile::where('employee_id',$id)->delete();
//        $employee->profile->delete();
        $employee->delete();

        $notification = array(
            'message' => 'Employee information trashed!',
            'alert-type' => 'warning'
        );

        return redirect()->route('employees')->with($notification);
    }

    public function trashes()
    {
        $employees = Employee::onlyTrashed()->get();
        $profiles = Profile::onlyTrashed()->get();
        return view('admin.employees.deactivated', compact('employees','profiles'));
    }

    public function restore($id)
    {
        $employee=Employee::where('id',$id)->onlyTrashed()->first();
        $profile=Profile::where('employee_id',$id)->onlyTrashed()->first();

        $employee->restore();
        $profile->restore();

        $notification = array(
            'message' => 'Employee information Restored!',
            'alert-type' => 'info'
        );

        return redirect()->route('employees')->with($notification);
    }

    public function delete($id)
    {
        $employee=Employee::where('id',$id)->onlyTrashed()->first();
        $profile=Profile::where('employee_id',$id)->onlyTrashed()->first();

        $employee->forceDelete();
        $profile->forceDelete();

        $notification = array(
            'message' => 'Employee Details are permanently deleted.',
            'alert-type' => 'error'
        );

        return redirect()->route('employees')->with($notification);
    }

    public function giveQuery(Request $request)
    {
        $employees = Employee::all();
        $admin_employee = Admin::with('employee')->where('username',Session::get('adminSession'))->first();
        if ($request->isMethod('post'))
        {
            $data = $request->all();
            $validateData = $request->validate([
               'employee_id'=>'required',
               'query'=>'required'
            ]);

            Query::create([
                'employee_id'=>$data['employee_id'],
                'query'=>$data['query'],
                'admin_id'=>$admin_employee->id
            ]);

            $notification = array(
                'message' => 'Query given to the selected employee is successfully saved!!',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        }
        return view('admin.employees.query',compact('employees'));

    }


}
