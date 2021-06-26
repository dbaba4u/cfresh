<?php

namespace App\Http\Controllers;

use App\Account;
use App\Admin;
use App\Commission;
use App\Employee;
use App\Expenses;
use App\Pay;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pay_types = Payment::all();
        return view('admin.employees.payments.index', compact('pay_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $rules =array(
            'type'=>'required|unique:payments'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        Payment::create([
            'type'=>$request->type
        ]);

        $notification = array(
            'message' => 'New Payment Type created successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('payments')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'type'=>'required'
        ]);

        $payment = Payment::findOrFail($id);

        $payment->type=$request->type;;

        $payment->save();

        $notification = array(
            'message' => 'Payment Type updated!',
            'alert-type' => 'success'
        );

        return redirect()->route('payments')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pay_type = Payment::findOrFail($id);
        $pay_type->delete();

        $notification = array(
            'message' => 'Payment-Type deleted and the record is updated',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    public function settle()
    {
        $today = Carbon::today()->format('Y-m-d');

        $commission_today = Commission::where('created_at',$today)->get();

        if (!empty($commission_today)) {
            foreach ($commission_today as $commission) {
                $employee_id = $commission->employee_id;
                $commission_amount = $commission->commission;
                $salary_amount = $commission->salary_amount;
                $salary_amount = empty($salary_amount) ? 0 : $salary_amount;
                $commission_amount = empty($commission_amount) ? 0 : $commission_amount;
                $amount = $commission_amount + $salary_amount;

                $pay = Pay::where('created_at',$today)->where('employee_id', $employee_id)->get();

                if (count($pay)>0)
                {
                    //Update
                    Pay::where('employee_id', $employee_id)->update(['amount'=>$amount]);
                }else
                {
                    //Create New
                    Pay::create([
                        'employee_id'=>$employee_id,
                        'amount'=>$amount,
                    ]);
                }

            }



        }

        // Get Employee Names
//        $employee_pays = Pay::select('employee_id')->get();
//        foreach($employee_pays as $pay){
//            $name = empty(Employee::where('id',$pay->employee_id)->first()) ? '' : Employee::where('id',$pay->employee_id)->first()->name;
////		    dd($name);
//            Pay::where('employee_id',$pay->employee_id)->update(['name'=>$name]);
//        }




        $pays = Pay::latest()->get();

        return view('admin.employees.settlement', compact('pays'));
    }

    public function pay($id,$paid, $neg_paid, $cash_type, $balance)
    {
                /*Existing Account*/
//        $paid = $balance == 'NaN' ? 0 : (float)$balance;
        $new_paid = (float) str_replace(',', '', $paid);
        $old_amount = (float) str_replace(',', '', $balance);

        $pay = Pay::where('id', $id)->where('status',0)->first();

        if (!empty($pay) ) {
            $balances = $old_amount - $new_paid;
            $pay->amount = $balances;
            $pay->save();

        } else {
            //        New Account
            Pay::create([
                'employee_id' => $id,
                'amount' => $new_paid,
            ]);
        }



        $user_id = Admin::getUser(Session::get('adminSession'))->id;

        $employee = Employee::findOrFail($pay->employee_id);
        $expense = new Expenses();
        $expense->amount = $new_paid;
        $expense->user_id = $user_id;
        $expense->cash_type = $cash_type;
        $expense->description = 'Commission Settlement to '.$employee->name;
        $expense->save();

        $account = new Account();
        $account->employee_id = $employee->id;
        $account->balance = $balances;
        $account->amount =$new_paid;
        $account->user_id = $user_id;
        $account->cash_type = $cash_type;
        $account->description = 'Commission Settlement to '.$employee->name;
        $account->save();

        //Update Employee Balance
        $employee = Employee::where('id',$pay->employee_id)->first();
        $employee->balance = $balances;
        $employee->save();

        $notification = array(
            'message' => 'An Employee is successfully Paid',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function getEmployees(Request $request)
    {
        $data = Pay::where('name', 'LIKE','%'.$request->keyword.'%')->get();
        return response()->json($data);
    }

    public function salary()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $salary = $employee->category->amount;

            $pay = Pay::where('employee_id', $employee->id)->first();

            if (!empty($pay))
            {
                $pay->increment('amount', $salary);
                $pay->save();
            }else
            {
                //Create New
                Pay::create([
                    'employee_id'=>$employee->id,
                    'amount'=>$salary
                ]);
            }

            //Update Expenses Table
//                $expense = new Pay();
//                $expense->amount = $salary;
//                $expense->user_id = 1;
//                $expense->employee = $employee->name;
//
//                $expense->expense_date = today();
//                $expense->description = 'Automated Salary Paid on 25th by 12:00 pm ';
//                $expense->save();

        }
        $current = Carbon::now()->month;
        $notification = array(
            'message' => 'Employees accounts has been debited with ' . $current . ' Salary',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
