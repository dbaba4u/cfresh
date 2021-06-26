@extends('admin.layouts.master')
{{--@section('klass','active')--}}

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-user-cog"></i> Admins Management </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                    <li><a href="#">Admins </i> </a> - </li>
                    <li class="active">Edit Admins</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <hr style="border: solid 0.5px #a3a3a3">
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-gradient-info">
                        {{--                       <span class="icon"><i class=""></i></span>--}}
                        <h5 class="card-title"><i class="fa fa-users"></i> Edit - <strong>Admins</strong></h5>
                    </div>
                    {{--                   <form action="{{route('admin.addAttribute', ['id'=>$product->id])}}" id="add_attribute" name="add_attribute" method="post" enctype="multipart/form-data">--}}
                    <form action="{{route('editAdmin',['id'=>$admin->id] )}}" method="post" id="addAdmin">
                        @csrf

                        <div class="card-body">
                            <div class="form-group row my-0" >
                                <label for="amount_type" class="col-sm-1 col-form-label text-right">Employee</label>
                                <div class="col-sm-2" style="margin-top: 0.3rem; ">
                                    <input type="text" id="employee_id" name="employee_id" value="{{!empty($admin->employee) ? $admin->employee->name : ''}}" class="form-control" readonly>
                                </div>

                            </div>
                            <hr class="my-1">

                            <div class="form-group row my-0" >
                                <label for="amount_type" class="col-sm-1 col-form-label text-right">Type</label>
                                <div class="col-sm-2" style="margin-top: 0.3rem; ">
                                    <input type="text" id="type" name="type" value="{{$admin->type}}" class="form-control" readonly>
                                </div>

                            </div>
                            <hr class="my-1">
                            <div class="form-group row my-0" >
                                <label for="username" class="col-sm-1 col-form-label text-right">Username</label>
                                <div class="col-sm-2" style="margin-top: 0.3rem">
                                    <input type="text" id="username" name="username" value="{{$admin->username}}" class="form-control" readonly>

                                </div>

                            </div>
                            <hr class="my-0">

                            <div class="form-group row my-0" >
                                <label for="password" class="col-sm-1 col-form-label text-right">Password</label>
                                <div class="col-sm-2" style="margin-top: 0.3rem">
                                    <input type="password" name="password" id="password"  class="form-control form-control-sm " >

                                </div>

                            </div>
                            <hr class="my-0">

                            <div class="form-group row other-field " id="access">
                                <label class="col-sm-1  text-right ">Access</label>

                               <div class="row mb-3 ml-5">
                                   {{--CUSTOMERS--}}
                                   <div class="panel panel-default border-left border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem">
                                       <div class="panel-heading" >Customers</div>
                                       <div class="panel-body ml-2" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="customer_view_access" id="customer_view_access" value="1" {{$admin->customer_view_access=='1' ? 'checked' : ''}}>
                                               <label for="customer_view_access" class="custom-control-label">View Customers</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="customer_view_order_access" id="customer_view_order_access" value="1" {{$admin->customer_view_order_access=='1' ? 'checked' : ''}}>
                                               <label for="customer_view_order_access" class="custom-control-label">View Customer's Orders</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="customer_add_access" id="customer_add_access" value="1" {{$admin->customer_add_access=='1' ? 'checked' : ''}}>
                                               <label for="customer_add_access" class="custom-control-label">Add Customer</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="customer_place_order_access" id="customer_place_order_access" value="1" {{$admin->customer_place_order_access=='1' ? 'checked' : ''}}>
                                               <label for="customer_place_order_access" class="custom-control-label">Place Order</label>
                                           </div>
                                       </div>
                                   </div>

                                   {{--EMPLOYEES--}}
                                   <div class="panel panel-default border-left border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem">
                                       <div class="panel-heading" >Employees</div>
                                       <div class="panel-body ml-2" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="employee_view_access" id="employee_view_access" value="1" {{$admin->employee_view_access=='1' ? 'checked' : ''}}>
                                               <label for="employee_view_access" class="custom-control-label">View Employees</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="employee_add_access" id="employee_add_access" value="1" {{$admin->employee_add_access=='1' ? 'checked' : ''}}>
                                               <label for="employee_add_access" class="custom-control-label">Add Employees</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="employee_deactivated_access" id="employee_deactivated_access" value="1" {{$admin->employee_deactivated_access=='1' ? 'checked' : ''}}>
                                               <label for="employee_deactivated_access" class="custom-control-label">Deactivate Employees</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="employee_category_access" id="employee_category_access" value="1" {{$admin->employee_category_access=='1' ? 'checked' : ''}}>
                                               <label for="employee_category_access" class="custom-control-label">Manage Categories</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="employee_pay_type_access" id="employee_pay_type_access" value="1" {{$admin->employee_pay_type_access=='1' ? 'checked' : ''}}>
                                               <label for="employee_pay_type_access" class="custom-control-label">Manage Payment Type</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="manage_queries_access" id="manage_queries_access" value="1" {{$admin->manage_queries_access=='1' ? 'checked' : ''}}>
                                               <label for="manage_queries_access" class="custom-control-label">Manage Queries</label>
                                           </div>
                                       </div>
                                   </div>

                                   {{--PRODUCTS--}}
                                   <div class="panel panel-default border-left border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem">
                                       <div class="panel-heading" >Products</div>
                                       <div class="panel-body ml-2" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="product_view_access" id="product_view_access" value="1" {{$admin->product_view_access=='1' ? 'checked' : ''}}>
                                               <label for="product_view_access" class="custom-control-label">View Products</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="product_add_access" id="product_add_access" value="1" {{$admin->product_add_access=='1' ? 'checked' : ''}}>
                                               <label for="product_add_access" class="custom-control-label">Add Product</label>
                                           </div>

                                       </div>
                                   </div>

                                   {{--ORDERS--}}
                                  {{-- <div class="panel panel-default border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem" >
                                       <div class="panel-heading" >Orders</div>
                                       <div class="panel-body" style="font-size: 0.8rem">
                                           <div class="custom-control custom-radio" style="margin: 3px">
                                               <input class="custom-control-input" type="radio" id="orders_view_access" name="orders_access" value="1" {{$admin->orders_access=='1' ? 'checked' : ''}}>
                                               <label class="custom-control-label" for="orders_view_access">View Only</label>
                                           </div>
                                           <div class="custom-control custom-radio" style="margin: 3px">
                                               <input class="custom-control-input" type="radio" id="orders_edit_access" name="orders_access" value="1" {{$admin->orders_access=='2' ? 'checked' : ''}}>
                                               <label class="custom-control-label " for="orders_edit_access">View, Add & Edit</label>
                                           </div>
                                           <div class="custom-control custom-radio" style="margin: 3px">
                                               <input class="custom-control-input" type="radio" id="orders_full_access" name="orders_access" value="1" {{$admin->orders_access=='3' ? 'checked' : ''}}>
                                               <label class="custom-control-label " for="orders_full_access">All</label>
                                           </div>
                                           <div class="custom-control custom-radio" style="margin: 3px">
                                               <input class="custom-control-input" type="radio" id="orders_none_access" name="orders_access" value="1" {{$admin->orders_access=='0' ? 'checked' : ''}}>
                                               <label class="custom-control-label " for="orders_none_access">None</label>
                                           </div>
                                       </div>
                                   </div>--}}

                                   {{--INVENTORIES--}}
                                   <div class="panel panel-default border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem" >
                                       <div class="panel-heading" >Inventories</div>
                                       <div class="panel-body" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="inventories_view_access" id="inventories_view_access" value="1" {{$admin->inventories_view_access=='1' ? 'checked' : ''}}>
                                               <label for="inventories_view_access" class="custom-control-label">Add Stock</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="inventories_batch_access" id="inventories_batch_access" value="1" {{$admin->inventories_batch_access=='1' ? 'checked' : ''}}>
                                               <label for="inventories_batch_access" class="custom-control-label">View Batch History</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="inventories_manage_access" id="inventories_manage_access" value="1" {{$admin->inventories_manage_access=='1' ? 'checked' : ''}}>
                                               <label for="inventories_manage_access" class="custom-control-label">Manage Raw Material</label>
                                           </div>

                                       </div>
                                   </div>

                                   {{--FINANCES--}}
                                   <div class="panel panel-default border-left border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem">
                                       <div class="panel-heading" >Finances</div>
                                       <div class="panel-body ml-2" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="income_view_access" id="income_view_access" value="1" {{$admin->income_view_access=='1' ? 'checked' : ''}}>
                                               <label for="income_view_access" class="custom-control-label">View Income</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="expenses_view_access" id="expenses_view_access" value="1" {{$admin->expenses_view_access=='1' ? 'checked' : ''}}>
                                               <label for="expenses_view_access" class="custom-control-label">View Expenses</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="income_add_access" id="income_add_access" value="1" {{$admin->income_add_access=='1' ? 'checked' : ''}}>
                                               <label for="income_add_access" class="custom-control-label">Add Income</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="expenses_add_access" id="expenses_add_access" value="1" {{$admin->expenses_add_access=='1' ? 'checked' : ''}}>
                                               <label for="expenses_add_access" class="custom-control-label">Add Expenses</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="finance_check_balance_access" id="finance_check_balance_access" value="1" {{$admin->finance_check_balance_access=='1' ? 'checked' : ''}}>
                                               <label for="finance_check_balance_access" class="custom-control-label">Manage Check & Balance</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="finance_cash_to_bank_access" id="finance_cash_to_bank_access" value="1" {{$admin->finance_cash_to_bank_access=='1' ? 'checked' : ''}}>
                                               <label for="finance_cash_to_bank_access" class="custom-control-label">Manage Cash to Bank</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="finance_cash_from_bank_access" id="finance_cash_from_bank_access" value="1" {{$admin->finance_cash_from_bank_access=='1' ? 'checked' : ''}}>
                                               <label for="finance_cash_from_bank_access" class="custom-control-label">Manage Cash from Bank</label>
                                           </div>
                                       </div>
                                   </div>

                                   {{--Operational Access--}}
                                   <div class="panel panel-default border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem" >
                                       <div class="panel-heading" >Operations</div>
                                       <div class="panel-body" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="store_move_access" id="store_move_access" value="1" {{$admin->store_move_access=='1' ? 'checked' : ''}}>
                                               <label for="store_move_access" class="custom-control-label">Move Product</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="damage_operation_access" id="damage_operation_access" value="1" {{$admin->operation_access=='1' ? 'checked' : ''}}>
                                               <label for="damage_operation_access" class="custom-control-label">Damage Operation</label>
                                           </div>
                                       </div>
                                   </div>

                                   {{--STORE--}}
                                   <div class="panel panel-default border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem" >
                                       <div class="panel-heading" >Store</div>
                                       <div class="panel-body" style="font-size: 0.8rem">

                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="store_view_access" id="store_view_access" value="1" {{$admin->store_view_access=='1' ? 'checked' : ''}}>
                                               <label for="store_view_access" class="custom-control-label">View Store </label>
                                           </div>

                                       </div>
                                   </div>

                                   {{--Coupon Access--}}
                                   <div class="panel panel-default border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem" >
                                       <div class="panel-heading" >Coupons</div>
                                       <div class="panel-body" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="view_coupon_access" id="view_coupon_access" value="1" {{$admin->view_coupon_access=='1' ? 'checked' : ''}}>
                                               <label for="view_coupon_access" class="custom-control-label">view Only</label>
                                           </div>
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="add_coupon_access" id="add_coupon_access" value="1" {{$admin->add_coupon_access=='1' ? 'checked' : ''}}>
                                               <label for="add_coupon_access" class="custom-control-label">Add Coupons</label>
                                           </div>

                                       </div>
                                   </div>

                                   {{--Payment Access--}}
                                   <div class="panel panel-default border-right" style="margin-top: 1rem; margin-left: 0.8rem; padding-right: 1rem" >
                                       <div class="panel-heading" >Employee Payments</div>
                                       <div class="panel-body" style="font-size: 0.8rem">
                                           <div class="custom-control custom-checkbox">
                                               <input class="custom-control-input" type="checkbox" name="payment_access" id="payment_access" value="1" {{$admin->payment_access=='1' ? 'checked' : ''}}>
                                               <label for="payment_access" class="custom-control-label">Make Payments</label>
                                           </div>


                                       </div>
                                   </div>
                               </div>

                            </div>

                            <hr class="my-1" >
                            <div class="form-group row my-0" style="margin-left: 7rem">
                                <div class="icheck-primary d-inline col-sm-1 text-left">
                                    <input type="checkbox" id="status" name="status" value="1" {{$admin->status=='1' ? 'checked' : ''}} class="form-control">
                                    <label for="status">
                                        Enable
                                    </label>
                                </div>
                            </div>
                            <hr class="my-1" >

                        </div>

                        <div class="card-footer text-center">
                            <input type="submit" value="Update Admin" class="btn btn-success">
                        </div>

                    </form>

                </div>
            </div>


        </div>
    </div>

@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            $("#view_batches").DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });

        $(document).ready(function () {
            var type = $('#type').val();
            if(type == 'Admin')
            {
                $('#access').hide();
            }else
            {
                $('#access').show();
            }

			$('#users_view_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='users_access']").val('1');
                }
            });
            $('#users_edit_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='users_access']").val('2');
                }
            });
            $('#users_full_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='users_access']").val('3');
                }
            });
            $('#users_none_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='users_access']").val('0');
                }
            });

            $('#employees_view_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='employees_access']").val('1');
                }
            });
            $('#employees_edit_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='employees_access']").val('2');
                }
            });
            $('#employees_full_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='employees_access']").val('3');
                }
            });
            $('#employees_none_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='employees_access']").val('0');
                }
            });

            $('#products_view_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='products_access']").val('1');
                }
            });
            $('#products_edit_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='products_access']").val('2');
                }
            });
            $('#products_full_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='products_access']").val('3');
                }
            });
            $('#products_none_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='products_access']").val('0');
                }
            });

            $('#orders_view_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='orders_access']").val('1');
                }
            });
            $('#orders_edit_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='orders_access']").val('2');
                }
            });
            $('#orders_full_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='orders_access']").val('3');
                }
            });
            $('#orders_none_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='orders_access']").val('0');
                }
            });

            $('#finance_view_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='finance_access']").val('1');
                }
            });
            $('#finance_edit_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='finance_access']").val('2');
                }
            });
            $('#finance_full_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='finance_access']").val('3');
                }
            });
            $('#finance_none_access').on('click', function () {
                if((this).checked)
                {
                    $("input[name='finance_access']").val('0');
                }
            });

        });

    </script>


    <script src="{{asset('js/scripts.js')}}"></script>



@endsection
