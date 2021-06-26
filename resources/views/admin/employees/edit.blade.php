
@extends('admin.layouts.master')
{{--@section('klass','active')--}}

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/ion.calendar.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap4_toggle.css')}}">
    <link rel="stylesheet" href="{{asset('css/comm_style.css.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/opensans-font.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/montserrat-font.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('backend/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css')}}">
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Employees' Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home </a> - </li>
                    <li><a href="#">Employees </i> </a> - </li>
                    <li class="active"> Employees' Detail</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <hr style="border: solid 0.5px #a3a3a3">
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Employee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="text-center">
                                <img class="profile-user-img img-responsive img-circle"
                                     src="{{asset(\App\Profile::where('employee_id', $employee->id)->first()->avatar)}}"
                                     alt="Employee profile picture">

                                <h3 class="profile-username text-center">{{$employee->name}}</h3>

                                <p class="text-muted text-center">{{$employee->category->name}}</p>
                            </div>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                            <p class="text-muted">{{\App\Profile::where('employee_id', $employee->id)->first()->address}}</p>

                            <hr>

                            <strong><i class="fas fa-phone mr-1"></i> Telephone</strong>

                            <p>{{\App\Profile::where('employee_id', $employee->id)->first()->phone}}</p>

                            <hr>

                            <p class="text-muted " >
                                Balance: &#8358 {{$employee->balance}}
{{--                                <span class="text-danger"> <strong>{{!empty(\App\Commission::where('employee_id',$employee->id)->latest('updated_at')->first()->balance)--}}
{{--                            ? \App\Commission::where('employee_id',$employee->id)->latest('updated_at')->first()->balance--}}
{{--                            : 0}}</strong></span>--}}
                            </p>

                            <p class="text-muted " >
                                Joined on {{$employee->joined}}
                            </p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Edit Profile</a></li>
                                @if($employee->category->payment->id == 1)
                                    <li class="nav-item"><a class="nav-link" href="#account_history" data-toggle="tab">Sales history</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#commission_settings" data-toggle="tab">Commission Settings</a></li>
                                @endif
                                <li class="nav-item"><a class="nav-link" href="#account" data-toggle="tab">Account</a></li>
                                <li class="nav-item"><a class="nav-link" href="#query" data-toggle="tab">Query</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <form action="{{route('employee.profile.update',['id'=>$employee->id])}}" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{$employee->name}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="category_id">Category</label>
                                                    <select name="category_id" id="category_id" class="form-control">
                                                        <option value="" selected disabled>Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{$category->id}}"
                                                                    @if($category->id == $employee->category->id)
                                                                    selected
                                                                @endif
                                                            >{{$category->name}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="joined">Joined on</label>
                                                    <input type="text" name="joined" id="datepicker" class="form-control" value="{{$employee->joined}}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" name="address" class="form-control" value="{{\App\Profile::where('employee_id', $employee->id)->first()->address}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="tel" name="phone"  class="form-control" value="{{\App\Profile::where('employee_id', $employee->id)->first()->phone}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Bank</label>
                                                    <select name="bank_id" class="form-control select2" style="width: 100%; height: 100% !important;">
                                                        {{--                                                <option value="0" disabled selected="selected"></option>--}}
                                                        @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}"
                                                                    @if($bank->id == $employee->bank->id)
                                                                    selected
                                                                @endif
                                                            >{{$bank->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="address">Account name</label>
                                                    <input type="text" name="account_name" class="form-control" value="{{$employee->account_name}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="address">Account number</label>
                                                    <input type="text" name="account_no" class="form-control" value="{{$employee->account_no}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="avatar">Upload picture</label>
                                                    <input type="file" name="avatar" class="form-control" >
                                                </div>

                                            </div>
                                            @if($employee->category->payment->id != 2)
{{--                                                <div class="col-lg-3">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label for="factor">Commission factor</label>--}}
{{--                                                        <input type="text" name="factor" class="form-control" readonly value="{{$employee->factor}}">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="target">Monthly Target</label>
                                                        <input type="text" name="target" class="form-control" value="{{$employee->target}}">
                                                    </div>
                                                </div>
                                           @endif

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa fa-save"></i> Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="tab-pane" id="account_history">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header p-2 bg-gradient-gray-dark">
                                                <strong>Sales</strong> - History
                                                <div class="float-right" >
                                                    <form action="{{route('employee.edit', ['id'=>$employee->id])}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row" style="font-size: 12px">
                                                            <label for="from" class="col-form-label">From</label>
                                                            <div class="col-md-4">
{{--                                                                <input class="form-control form-control-sm myInput" id="sales_from" name="from" data-lang="en"   />--}}
{{--                                                                <input type="text" class="form-control form-control-sm myCalendar" id="sales_from" >--}}
                                                                <input type="date" class="form-control form-control-sm " id="from" name="from">
                                                            </div>
                                                            <label for="from" class="col-form-label">To</label>
                                                            <div class="col-md-4">
                                                                <input type="date" class="form-control form-control-sm" id="to" name="to">
{{--                                                                <input class="form-control form-control-sm myInput" id="sales_to" name="to" data-lang="en"  >--}}
                                                            </div>

                                                            <div class="col-md-1">
                                                                <button type="submit" class="btn btn-primary btn-xs" name="search" >Search</button>
                                                            </div>&nbsp;
                                                            <div class="col-md-1">
                                                                <button type="submit" class="btn btn-success btn-xs" name="exportPDF" >ExportPDF</button>
                                                            </div>
                                                        </div>
                                                        {{--                                </div>--}}
                                                    </form>
                                                </div>
                                            </div><!-- /.card-header -->

                                            <div class="card card-info">

                                                <div class="card-body">
                                                    <div class="table-responsive" style="font-size: 0.8rem">
                                                        <table id="sale_history" class="table table-striped table-bordered table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                                <th>Sales Rep.</th>
                                                                <th>Quantities </th>
                                                                <th>Target </th>
                                                                <th>Customer </th>
                                                                <th>Status</th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $sum1 = 0; $sum2 =0 ?>
                                                            @foreach($orders as $order)
                                                                <tr>
                                                                    <td>{{\Carbon\Carbon::parse($order->created_at)->toDateString()}}</td>
                                                                    <td>{{\Carbon\Carbon::parse($order->created_at)->format('H:i A')}}</td>
                                                                    <td>{{$order->employee->name}}</td>
                                                                    <td>{{$sum1 = $order->orders->sum('product_qty')}}</td>
                                                                    <?php $sum2 =$sum1+$sum2 ?>
                                                                    <td>
                                                                        @if($target != 0)
                                                                        {{round($sum2*100/$target)}}%
                                                                        @endif
                                                                    </td>
                                                                    <td>{{$order->user->name}}</td>
                                                                    <td class="text-center">
                                                                        @if($target != 0)
                                                                           @if($sum2<=(int)(0.5*($target)))
                                                                            <span style="color: Red"><strong>{{'Under Performed'}}</strong></span>
                                                                           @elseif($sum2>=(int)(0.5*($target)) && $sum2<=(int)(0.75*($target)))
                                                                            <span style="color: darkred"><strong>{{'Fair'}}</strong></span>

                                                                            @elseif($sum2>=(int)(0.75*($target)) && $sum2<(int)(1*($target)))
                                                                            <span style="color: blue"><strong>{{'Almost There ...'}}</strong></span>

                                                                            @elseif($sum2==(int)(1*($target)))
                                                                                <span style="color: blue"><strong>{{'Target Met'}}</strong></span>

                                                                            @elseif($sum2>=(int)(1*($target)) && $sum2<=(int)(1.25*($target)))
                                                                             <span style="color: darkblue"><strong>{{'Good'}}</strong></span>
                                                                            @elseif($sum2>=(int)(1.25*($target)))
                                                                            <span style="color: midnightblue"><strong>{{'Excellent'}}</strong></span>
                                                                           @endif
                                                                         @endif
                                                                    </td>


                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                        </div>'

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="commission_settings">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{route('employee.edit.commission', ['id'=>$employee->id])}}" method="post" id="commissionForm">
                                            @csrf

                                            <input type="hidden" name="curr_employee" id="curr_employee" value="{{$employee->id}}">
                                        <div class="card">
                                            <div class="card-header text-center bg-gradient-light">
                                                <strong><h3 class="card-title">{{strtoupper('Commission Record')}}</h3></strong>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <div class="form-row justify-content-center" >
                                                    <div class="col-md-3 ">
                                                        <label for="customer">Customer</label>
                                                        <select class="form-control form-control-sm" id="customer" name="customer_id" required>
                                                            <option value="" selected disabled>Select Customer</option>
                                                            @foreach($customers as $customer)
                                                                <option value="{{$customer->id}}" >{{$customer->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label class="sfactor">Factor</label>
                                                        <input class="form-control form-control-sm sfactor" type="number" id="sfactor" name="sfactor" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="expire_date">Expire Date</label>
                                                        <input type="date" id="expire_date" name="expire_date" required class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <hr class="my-1">
                                                <div class="row justify-content-center" id="seles_rep_section" >
                                                    <div class="table-responsive col-md-4 tabule" style="font-size: 0.7rem" >
                                                        <table >
                                                            <tbody id="comm_body">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                            <div class="text-center">
                                                <button id="addRows" class="btn btn-info "><i class="fa fa-plus"></i> Add Sales Rep(s). </button>
                                            </div>
                                            <hr class="my-1">
                                            <div class="card-footer text-center" style="padding-top: -10px">
                                                <button type="submit" style="width: 230px"  class="btn btn-outline-primary" id="add"> <i class="fa fa-save"></i> Save</button>
                                            </div>
                                        </div>
                                        </form>
                                        <div class="card">
                                            <div class="card-header p-2 bg-gradient-gray-dark">
                                                <strong><i class="fa fa-money-bill"></i> Commission Settings</strong> - Details
                                            </div><!-- /.card-header -->
                                            <div class="card card-info">
                                                <div class="card-body">
                                                    <div class="table-responsive" style="font-size: 0.8rem">
                                                        <table id="comm_list" class="table table-striped table-bordered table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th>Customer</th>
                                                                <th>Sales Rep</th>
                                                                <th>Factor </th>
                                                                <th> </th>
                                                                <th>Expire Date</th>
                                                                <th>Date Joined</th>
                                                                <th>User</th>
                                                                <th>Created Date</th>
                                                                <th>Status</th>
                                                                <th></th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            @foreach($commission_settings as $settings)
                                                                <tr>
                                                                    <td>
                                                                       {{\App\User::where('id', $settings->customer_id)->first()->name}}
                                                                    </td>
                                                                    @if((int)$settings->sales_reps > 0 )
                                                                        <td>{{$employee->name}}</td>
                                                                    @else
                                                                        <td>{{$settings->sales_reps}}</td>
                                                                    @endif


                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <div class="input-group-prepend ">
                                                                                <div class="input-group-text  text-purple"><strong>{{$settings->factor}}</strong></div>
                                                                            </div>
                                                                            <input type="text" name="factor" id="factor" style="width: 60px" class="form-control form-control-sm factor_{{$settings->id}}" value="{{$settings->factor}}">
                                                                        </div>
                                                                    </td>
                                                                    <td><button rel="{{$settings->id}}" class="btn btn-outline-info btn-sm add-setting btn-block">Add</button></td>

                                                                    <td>{{\Carbon\Carbon::parse($settings->expire_date)->toFormattedDateString()}}</td>
                                                                    <td>{{\Carbon\Carbon::parse(\App\User::where('id', $settings->customer_id)->first()->created_at)->toFormattedDateString()}}</td>
                                                                    <td></td>
{{--                                                                    <td>{{\App\Admin::where('id',$settings->admin_employee_id)->first()->employee->name}}</td>--}}
                                                                    <td>{{\Carbon\Carbon::parse($settings->created_at)->toFormattedDateString()}}</td>

                                                                        @if($settings->status == 0)
                                                                        <td class="text-bold text-danger bg-danger text-center">
                                                                            Expired
                                                                        </td>
                                                                        @else
                                                                        <td class="text-bold text-success bg-success text-center">
                                                                            Active
                                                                        </td>
                                                                        @endif

                                                                    <td class="text-center">
                                                                        <a rel="{{$settings->id}}" href="javascript:"  id="delete_settings" role="button" class="delete_settings btn btn-danger btn-sm">Delete</a>
{{--                                                                        <a href="{{route('deleteCommissionSetting', ['id'=>$settings->id])}}" id="delete_commSettings" role="button" class="delete_coupon btn btn-danger btn-sm">Delete</a>--}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="account">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header p-2 bg-gradient-gray-dark">
                                                <strong>Account</strong> - History
                                                <div class="float-right" >
                                                    <form action="{{route('employee.edit.account', ['id'=>$employee->id])}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row" style="font-size: 12px">
                                                            <label for="from" class="col-form-label">From</label>
                                                            <div class="col-md-4">
                                                                {{--                                                                <input class="form-control form-control-sm myInput" id="sales_from" name="from" data-lang="en"   />--}}
                                                                {{--                                                                <input type="text" class="form-control form-control-sm myCalendar" id="sales_from" >--}}
                                                                <input type="date" class="form-control form-control-sm " id="from" name="from">
                                                            </div>
                                                            <label for="from" class="col-form-label">To</label>
                                                            <div class="col-md-4">
                                                                <input type="date" class="form-control form-control-sm" id="to" name="to">
                                                                {{--                                                                <input class="form-control form-control-sm myInput" id="sales_to" name="to" data-lang="en"  >--}}
                                                            </div>

                                                            <div class="col-md-1">
                                                                <button type="submit" class="btn btn-primary btn-xs" name="search" >Search</button>
                                                            </div>&nbsp;
                                                            <div class="col-md-1">
                                                                <button type="submit" class="btn btn-success btn-xs" name="exportPDF" >ExportPDF</button>
                                                            </div>
                                                        </div>
                                                        {{--                                </div>--}}
                                                    </form>
                                                </div>
                                            </div><!-- /.card-header -->

                                            <div class="card card-info">

                                                <div class="card-body">
                                                    <div class="table-responsive" style="font-size: 0.8rem">
                                                        <table id="account_table" class="table table-striped table-bordered table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                                <th>Amount</th>
                                                                <th>Balance </th>
                                                                <th>Payment Type </th>
                                                                <th>User </th>
                                                                <th>Description </th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($accounts as $account)
                                                                <tr>
                                                                    <td>{{\Carbon\Carbon::parse($account->created_at)->toDateString()}}</td>
                                                                    <td>{{\Carbon\Carbon::parse($account->created_at)->format('H:i A')}}</td>
                                                                    <td>{{$account->amount}}</td>
                                                                    <td>{{$account->balance}}</td>
                                                                    <td>{{$account->cash_type}}</td>
                                                                    <td>
                                                                        @if(!empty(\App\Admin::where('id',$account->user_id)->first()))
                                                                            {{\App\Employee::where('id',\App\Admin::where('id',$account->user_id)->first()['employee_id'])->first()['name']}}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{$account->description}}</td>

                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                        </div>'

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="query">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header p-2 bg-gradient-gray-dark">
                                                <strong>Query</strong> - Summary
                                                {{--<div class="float-right" >
                                                    <form action="{{route('employee.edit.account', ['id'=>$employee->id])}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row" style="font-size: 12px">
                                                            <label for="from" class="col-form-label">From</label>
                                                            <div class="col-md-4">
                                                                --}}{{--                                                                <input class="form-control form-control-sm myInput" id="sales_from" name="from" data-lang="en"   />--}}{{--
                                                                --}}{{--                                                                <input type="text" class="form-control form-control-sm myCalendar" id="sales_from" >--}}{{--
                                                                <input type="date" class="form-control form-control-sm " id="from" name="from">
                                                            </div>
                                                            <label for="from" class="col-form-label">To</label>
                                                            <div class="col-md-4">
                                                                <input type="date" class="form-control form-control-sm" id="to" name="to">
                                                                --}}{{--                                                                <input class="form-control form-control-sm myInput" id="sales_to" name="to" data-lang="en"  >--}}{{--
                                                            </div>

                                                            <div class="col-md-1">
                                                                <button type="submit" class="btn btn-primary btn-xs" name="search" >Search</button>
                                                            </div>&nbsp;
                                                            <div class="col-md-1">
                                                                <button type="submit" class="btn btn-success btn-xs" name="exportPDF" >ExportPDF</button>
                                                            </div>
                                                        </div>
                                                        --}}{{--                                </div>--}}{{--
                                                    </form>
                                                </div>--}}
                                            </div><!-- /.card-header -->

                                            <div class="card card-info">

                                                <div class="card-body">
                                                    <div class="table-responsive" style="font-size: 0.8rem">
                                                        <table id="query_table" class="table table-striped table-bordered table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                                <th>Query Contents</th>
                                                                <th>User </th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($queries as $query)
                                                                <tr>
                                                                    <td>{{\Carbon\Carbon::parse($query->created_at)->toDateString()}}</td>
                                                                    <td>{{\Carbon\Carbon::parse($query->created_at)->format('H:i A')}}</td>
                                                                    <td>{{$query->query}}</td>
                                                                    <td>{{ $query->admin->employee->name}}</td>

                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                        </div>'

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->

    </section>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/bootstrap4-toggle.min.js')}}"></script>

    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('js/jszip.min.js')}}"></script>
    <script src="{{asset('js/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/vfs_fonts.js')}}"></script>
    <script src="{{asset('js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('js/buttons.print.min.js')}}"></script>

    <script src="{{asset('js/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('js/ion.calendar.min.js')}}"></script>
    <script src="{{asset('js/comm_script.min.js')}}"></script>

    <script>
        var html = '';
        $(function () {
            $("#sale_history").DataTable({
                "responsive": true,
                "autoWidth": false,
                "aaSorting": [ [0,'desc'], [1,'desc'] ],
                dom: 'Bfrtip',
                lengthMenu: [[10,25,50,100, -1], [10, 25, 50,100, "All"]],
                buttons: [
                    'excel',
                    {
                        extend: "pdfHtml5",

                        customize: function(doc) {
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        {
                                            alignment: 'center',
                                            text: [
                                                { text: page.toString(), italics: true },
                                                ' of ',
                                                { text: pages.toString(), italics: true }
                                            ]
                                        }],
                                    margin: [10, 0]
                                }
                            });
                        }
                    },
                    'print',
                    'pageLength'
                ]
            });

            // Append a caption to the table before the DataTables initialisation
            // $('#account_table').append('<caption class="text-center" style="font-size: 18px; caption-side: Bottom">A fictional company\'s staff table.</caption>');
            $("#comm_list").DataTable({
                "responsive": true,
                "autoWidth": false,
                "footer": true,
                "aaSorting": [ [0,'desc'], [1,'desc'] ],
                dom: 'Bfrtip',
                lengthMenu: [[10,25,50,100, -1], [10, 25, 50,100, "All"]],
                buttons: [
                     'excel',
                    {
                        extend: "pdfHtml5",

                        customize: function(doc) {
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        {
                                            alignment: 'center',
                                            text: [
                                                { text: page.toString(), italics: true },
                                                ' of ',
                                                { text: pages.toString(), italics: true }
                                            ]
                                        }],
                                    margin: [10, 0]
                                }
                            });
                        }
                    },
                    'print',
                    'pageLength'
                ]
            });
            var table = $("#account_table").DataTable({
                "responsive": true,
                "autoWidth": false,
                "footer": true,
                "aaSorting": [ [0,'desc'], [1,'desc'] ],
                dom: 'Bfrtip',
                lengthMenu: [[10,25,50,100, -1], [10, 25, 50,100, "All"]],
                buttons: [
                     'excel',
                    {
                        extend: "pdfHtml5",

                        customize: function(doc) {
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        {
                                            alignment: 'center',
                                            text: [
                                                { text: page.toString(), italics: true },
                                                ' of ',
                                                { text: pages.toString(), italics: true }
                                            ]
                                        }],
                                    margin: [10, 0]
                                }
                            });
                        }
                    },
                    'print',
                    'pageLength'
                ]
            });

            var table = $("#query_table").DataTable({
                "responsive": true,
                "autoWidth": false,
                "footer": true,
                "aaSorting": [ [0,'desc'], [1,'desc'] ],
                dom: 'Bfrtip',
                lengthMenu: [[10,25,50,100, -1], [10, 25, 50,100, "All"]],
                buttons: [
                    'excel',
                    {
                        extend: "pdfHtml5",

                        customize: function(doc) {
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        {
                                            alignment: 'center',
                                            text: [
                                                { text: page.toString(), italics: true },
                                                ' of ',
                                                { text: pages.toString(), italics: true }
                                            ]
                                        }],
                                    margin: [10, 0]
                                }
                            });
                        }
                    },
                    'print',
                    'pageLength'
                ]
            });

            $('#comm_body').sortable();

            $('#addRows').click(function (e) {
                e.preventDefault();
                $('.sfactor').attr('disabled',true).attr('required',false).hide();
                addNewRow();
            });
            var n = 0;
            var x = 0;
            function addNewRow() {
                $.ajax({
                    url: 'add/new_comm',
                    method: 'post',
                    data: {getNewOrderItem:1},
                    success: function (data) {
                        $('#comm_body').append(data);
                        $('.number').each(function () {
                            $(this).html(++n);
                        });
                        x++;
                    }
                })
            }

            $(document).on('click', '.remove_rep', function () {
               $(this).parents('tr').remove();
               --x;
                if (x == 0){
                    $('.sfactor').attr('disabled',false).attr('required',true).show();
                }
            });

        });
        $(function () {
            $('.add-setting').on('click',function (e) {
                e.preventDefault();
                var id = $(this).attr('rel');
                var factor = $('.factor_'+id).val();

                if(factor == 0){
                    alert('Please enter amount Paid for this commission.');
                    return false;
                }

                window.location.href = '/admin/employee/add_factor/'+id+'/'+factor;

            });
        })
    </script>

    <script>
        $(document).on('click', '.delete_settings', function (e) {
            var id = $(this).attr('rel');
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record again!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    window.location.href = '/admin/delete/commission/setting/'+id;
                });

        })
    </script>

@endsection
