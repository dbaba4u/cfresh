<?php
;
?>
@extends('admin.layouts.master')
{{--@section('klass','active')--}}

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-store"></i> Customers </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                                        <li><a href="#">Customers </i> </a> - </li>
                    <li class="active"> All Customers</li>
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
                    <div class="card-header p-2 bg-gradient-gray-dark">
                        <strong><i class="fa fa-store-alt"></i> All</strong> - Registered Customers
                        <div class="float-right" >
                            <form action="{{route('customer.search')}}" method="POST" >
                                @csrf

                                <div class="row float-right " style="font-size: 12px">
                                    <div class="col-md-6">
                                        <div class="form-group row mb-0">
{{--                                            <label>Date range:</label>--}}
                                            <div class="input-group" style="width: 350px">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                  </span>
                                                </div>
                                                <input type="text" name="search_range" class="form-control float-right" id="reservation">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>


{{--                                    <div class="col-md-1">--}}
{{--                                        <button type="submit" class="btn btn-primary btn-sm" name="search"  style="height: 37px">Search</button>--}}
{{--                                    </div>--}}
                                    &nbsp;
                                    &nbsp;
                                    &nbsp;
                                    &nbsp;
                                    &nbsp;
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-success btn-sm" name="exportPDF" style="height: 37px">ExportPDF</button>
                                    </div>
                                    &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{{--                                    <div class="col-md-3 float-right">--}}
{{--                                        <a href="{{route('customer.create')}}" class="btn btn-info btn-sm" role="button">New Record</a>--}}
{{--                                    </div>--}}
                                </div>
                                {{--                                </div>--}}
                            </form>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card card-info">

                        <div class="card-body">
                            <div class="table-responsive" style="font-size: 0.8rem">
                                <table id="store_list" class="table table-striped table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Name </th>
                                        <th>Address</th>
                                        <th>State</th>
                                        <th>Lga</th>
                                        <th>Phone</th>
                                        <th>Balance</th>
                                        <th>Sale Rep.</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($customer->created_at)->toFormattedDateString()}}</td>
                                            <td>{{\Carbon\Carbon::parse($customer->created_at)->format('H:i:s A')}}</td>
                                            <td><a href="{{route('customer.orderHistory', ['id'=>$customer->id])}}">{{$customer->name}}</a></td>

                                            <td>{{$customer->address}}</td>
                                           <td>{{!empty($customer->state) ? $customer->state->name : ''}}</td>
                                           <td>{{!empty($customer->lga) ? $customer->lga->name : ''}}</td>
                                            <td>{{$customer->mobile}}</td>
                                            <td> &#8358 {{number_format(\App\Order::where('user_id',$customer->id)->sum('balance'),2)}}</td>
{{--                                            <td>{{$customer->old_balance}}</td>--}}
                                            <td>
                                                {{!empty(\App\Employee::where('id', $customer->vendor)->first()) ? \App\Employee::where('id', $customer->vendor)->first()['name'] : ''}}</td>

                                            @if($customer->status == 1)
                                                <td class="text-danger text-bold text-center" ><strong>Active</strong></td>
                                            @else
                                                <td class="text-success text-bold text-center" ><strong>Not active</strong></td>
                                            @endif
                                            <td >
                                                <a role="button" href="{{route('customers.create_step2', ['id'=>$customer->id])}}"  class="fa fa-edit text-success"></a> |
                                                @if($customer->status == 1)
                                                <a href="{{route('customer.deactivate', ['id'=>$customer->id])}}" class="fa fa-user-alt-slash text-danger"></a>
                                                 @else
                                                    <a href="{{route('customer.activate', ['id'=>$customer->id])}}" class="fa fa-user-check text-success"></a>
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
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

    <script>
        $(function () {
            $("#store_list").DataTable({
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

            // range picker
            $('#reservation').daterangepicker()

           /* $("#caps_list").DataTable({
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
            $("#labels_list").DataTable({
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
            });*/
        })
    </script>

@endsection
