<?php
//
//namespace App\Console\Commands;
//
//use App\Employee;
//use App\Expenses;
//use App\Pay;
//use Illuminate\Console\Command;
//
//class PaySalary extends Command
//{
//    /**
//     * The name and signature of the console command.
//     *
//     * @var string
//     */
////    protected $signature = 'command:pay_salary';
//
//    /**
//     * The console command description.
//     *
//     * @var string
//     */
//    protected $description = 'Pay Monthly Salary';
//
//    /**
//     * Create a new command instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        parent::__construct();
//    }
//
//    /**
//     * Execute the console command.
//     *
//     * @return int
//     */
//    public function handle()
//    {
////        $employees = Employee::all();
//
//        /*foreach ($employees as $employee) {
//            $salary = $employee->category->amount;
//            if ($employee->payment_id != 1 && $employee->payment_id !=null)
//            {
//                $pay = Pay::where('employee_id', $employee->id)->first();
//
//                if (!empty($pay))
//                {
//                    $pay->increment('amount', $salary);
//                }else
//                {
//                    //Create New
//                    Pay::create([
//                        'employee_id'=>$employee->id,
//                        'amount'=>$salary,
//                    ]);
//                }
//
//                //Update Expenses Table
////                $expense = new Pay();
////                $expense->amount = $salary;
////                $expense->user_id = 1;
////                $expense->employee = $employee->name;
////
////                $expense->expense_date = today();
////                $expense->description = 'Automated Salary Paid on 25th by 12:00 pm ';
////                $expense->save();
//            }
//        }*/
//
//        $notification = array(
//            'message' => 'Employees Salary Has just been Paid',
//            'alert-type' => 'success'
//        );
//
//        return redirect()->route('admin.dashboard')->with($notification);
//    }
//}
