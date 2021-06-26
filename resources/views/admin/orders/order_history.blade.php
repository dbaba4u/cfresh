@extends('admin.layouts.master')
{{--@section('klass','active')--}}

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.dataTables.min.css')}}">
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-shopping-bag"></i> Orders </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                    <li><a href="#">Orders </i> </a> - </li>
                    <li class="active"> Order history</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <hr style="border: solid 0.5px #a3a3a3">
@endsection


@section('content')
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @include('frontend.includes.msgs')
{{--                    <div class="card-header p-2 bg-gradient-gray-dark">--}}
{{--                        <strong><i class="fa fa-shopping-bag"></i> Order</strong> - History--}}
{{--                        <div class="float-right">{{$user->name}}-({{$user->email}})</div>--}}
{{--                    </div><!-- /.card-header -->--}}
                    <div class="card-header p-2 bg-gradient-gray-dark">
                        <strong><i class="fa fa-shopping-bag"></i> Order</strong> - History ({{$user->name}})
                        <div class="float-right" >
                            <form action="{{route('admin.orderHistory',['user_id'=>$user->id])}}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{--                                <div class="container">--}}
                                    <div class="row" style="font-size: 12px">
                                        <label for="from" class="col-form-label">From</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control form-control-sm " id="from" name="from">
                                        </div>
                                        <label for="from" class="col-form-label">To</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control form-control-sm" id="to" name="to">
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
                                <table id="orders_list" class="table table-striped table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th>Order Date</th>
                                        <th>Time</th>
                                        <th>Ordered Product</th>
                                        <th>Order Amount (&#8358)</th>
                                        <th>Amount Paid (&#8358)</th>
                                        <th>Balance (&#8358)</th>
                                        <th>Discount (&#8358)</th>
                                        <th>Coupon Code</th>
                                        <th>Mobile</th>
                                        <th>Payment Method</th>
{{--                                        <th>User</th>--}}
                                        <th>Sales Reps.</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ViewsPage as $ViewsPages)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($ViewsPages->created_at)->toDateString()}}</td>
                                            <td>{{\Carbon\Carbon::parse($ViewsPages->created_at)->format('H:i:s A')}}</td>
                                            <td>
                                                <a href="{{route('admin.viewOrderDetails',['id'=>$ViewsPages->id])}}">
                                                    @foreach($ViewsPages->orders as $pro)
                                                        {{$pro->product_name}} - ({{$pro->product_qty}})
                                                        <br>
                                                    @endforeach
                                                </a>

                                            </td>
                                            <td>{{number_format($ViewsPages->grand_total,2 )}}</td>
                                            <td>{{number_format($ViewsPages->amount_paid, 2)}}</td>
                                            <td>{{number_format($ViewsPages->balance,2 )}}</td>
                                            <td>{{number_format($ViewsPages->coupon_amount, 2)}}</td>

                                            <td>{{$ViewsPages->coupon_code}}</td>
                                            <td>{{$ViewsPages->mobile}}</td>
                                            <td>{{$ViewsPages->payment_method}}</td>
{{--                                            <td>--}}
{{--                                                <?php $admin_user =  \App\Admin::where('id', $order->admin_id)->first(); ?>--}}
{{--                                                @if(!empty($admin_user))--}}
{{--                                                    {{$admin_user->employee->name}}--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
                                            <td>
                                                @if($ViewsPages->employee_id != 0)
                                                    {{!empty(\App\Employee::where('id', $ViewsPages->employee_id)->withTrashed()->first()['name']) ?
                                                   \App\Employee::where('id', $ViewsPages->employee_id)->withTrashed()->first()['name'] : ''}}
{{--                                                     {{\App\Employee::where('id', $ViewsPages->employee_id)->first()->name}}--}}
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
                </div>

            </div>
        </div>

        {{--        @include('admin.includes.add_inventory_modal')--}}
        @include('admin.includes.errors')
        @include('admin.includes.move_material_modal')

    </section>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('js/jszip.min.js')}}"></script>
    <script src="{{asset('js/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('js/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/vfs_fonts.js')}}"></script>
    <script src="{{asset('js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('js/buttons.print.min.js')}}"></script>

    <script>
        $(function () {
            $("#orders_list").DataTable({
                "responsive": true,
                "autoWidth": false,
                "footer": true,
                "aaSorting": [ [0,'desc'], [1,'desc'] ],
                dom: 'Bfrtip',
                lengthMenu: [[10,25,50,100, -1], [10, 25, 50,100, "All"]],
                buttons: [
                    'colvis',
                    'excel',
                    {
                        extend: "pdfHtml5",
                        // orientation: 'landscape',
                        exportOptions: {
                            columns: ':visible'
                        },
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
                ],
                columnDefs: [ {
                    targets: -1,
                    visible: false
                } ]
            });
        })
    </script>

    <script src="{{asset('js/scripts.js')}}"></script>
@endsection
