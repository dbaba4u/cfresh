<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link href="{{asset('css/sweetalert2.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    @yield('head')
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper" >
    <!-- Navbar -->
    @include('admin.includes.top_nav')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.includes.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"  >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('page-header')
        </section>

        <!-- Main content -->
        <section class="content">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
{{--        <div class="float-right d-none d-sm-block">--}}
{{--            <b>Version</b> 3.0.3-pre--}}
{{--        </div>--}}
        <strong>Copyright &copy; 2020-2021 <a href="{{route('home')}}">cfresh.org</a>.</strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
{{--    <aside class="control-sidebar control-sidebar-dark">--}}
{{--        <!-- Control sidebar content goes here -->--}}
{{--    </aside>--}}
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="{{asset('js/all.js')}}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>


@yield('scripts')
<script>
    $(function () {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
<script>
        @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
    @endif
</script>

<script>
    $(document).on('click', '.salary_payment', function (e) {
        e.preventDefault();
        // var id = $(this).attr('rel');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Please!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                Swal.fire(
                    "Debited!",
                    "Employees accounts has been debited for this month salary",
                    "success",
                    $.ajax({
                        url: '/admin/employees/salaries',
                        type: 'GET',
                        success: function() {

                        }
                    }),

                );
                // location.reload()
                setTimeout(function () { document.location.reload(true); }, 5000);
            } else if (result.dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Your file is safe :)",
                    "error"
                )
            }
        });

    });
</script>

<div class="daterangepicker ltr show-calendar opensright" style="top: 1438px; left: 1143px; right: auto; display: none;">
    <div class="ranges"></div>
    <div class="drp-calendar left">
        <div class="calendar-table">
            <table class="table-condensed">
                <thead>
                <tr>
                    <th class="prev available">
                        <span></span>
                    </th>
                    <th colspan="5" class="month">Oct 2020</th>
                    <th></th>
                </tr>
                <tr>
                    <th>Su</th>
                    <th>Mo</th>
                    <th>Tu</th>
                    <th>We</th>
                    <th>Th</th>
                    <th>Fr</th>
                    <th>Sa</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="weekend off ends available" data-title="r0c0">27</td>
                    <td class="off ends available" data-title="r0c1">28</td>
                    <td class="off ends available" data-title="r0c2">29</td>
                    <td class="off ends available" data-title="r0c3">30</td>
                    <td class="available" data-title="r0c4">1</td>
                    <td class="available" data-title="r0c5">2</td>
                    <td class="weekend available" data-title="r0c6">3</td>
                </tr>
                <tr>
                    <td class="weekend available" data-title="r1c0">4</td>
                    <td class="active start-date available" data-title="r1c1">5</td>
                    <td class="in-range available" data-title="r1c2">6</td>
                    <td class="in-range available" data-title="r1c3">7</td>
                    <td class="in-range available" data-title="r1c4">8</td>
                    <td class="in-range available" data-title="r1c5">9</td>
                    <td class="weekend in-range available" data-title="r1c6">10</td>
                </tr>
                <tr>
                    <td class="weekend in-range available" data-title="r2c0">11</td>
                    <td class="in-range available" data-title="r2c1">12</td>
                    <td class="in-range available" data-title="r2c2">13</td>
                    <td class="in-range available" data-title="r2c3">14</td>
                    <td class="in-range available" data-title="r2c4">15</td>
                    <td class="in-range available" data-title="r2c5">16</td>
                    <td class="weekend in-range available" data-title="r2c6">17</td>
                </tr>
                <tr>
                    <td class="today weekend in-range available" data-title="r3c0">18</td>
                    <td class="in-range available" data-title="r3c1">19</td>
                    <td class="in-range available" data-title="r3c2">20</td>
                    <td class="in-range available" data-title="r3c3">21</td>
                    <td class="in-range available" data-title="r3c4">22</td>
                    <td class="in-range available" data-title="r3c5">23</td>
                    <td class="weekend in-range available" data-title="r3c6">24</td>
                </tr>
                <tr>
                    <td class="weekend in-range available" data-title="r4c0">25</td>
                    <td class="in-range available" data-title="r4c1">26</td>
                    <td class="in-range available" data-title="r4c2">27</td>
                    <td class="in-range available" data-title="r4c3">28</td>
                    <td class="in-range available" data-title="r4c4">29</td>
                    <td class="in-range available" data-title="r4c5">30</td>
                    <td class="weekend in-range available" data-title="r4c6">31</td>
                </tr>
                <tr>
                    <td class="weekend off ends in-range available" data-title="r5c0">1</td>
                    <td class="off ends in-range available" data-title="r5c1">2</td>
                    <td class="off ends in-range available" data-title="r5c2">3</td>
                    <td class="off ends in-range available" data-title="r5c3">4</td>
                    <td class="off ends in-range available" data-title="r5c4">5</td>
                    <td class="off ends in-range available" data-title="r5c5">6</td>
                    <td class="weekend off ends in-range available" data-title="r5c6">7</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right">
        <div class="calendar-table">
            <table class="table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <th colspan="5" class="month">Nov 2020</th>
                    <th class="next available"><span></span></th>
                </tr>
                <tr>
                    <th>Su</th>
                    <th>Mo</th>
                    <th>Tu</th>
                    <th>We</th>
                    <th>Th</th>
                    <th>Fr</th>
                    <th>Sa</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="weekend off ends in-range available" data-title="r0c0">25</td>
                    <td class="off ends in-range available" data-title="r0c1">26</td>
                    <td class="off ends in-range available" data-title="r0c2">27</td>
                    <td class="off ends in-range available" data-title="r0c3">28</td>
                    <td class="off ends in-range available" data-title="r0c4">29</td>
                    <td class="off ends in-range available" data-title="r0c5">30</td>
                    <td class="weekend off ends in-range available" data-title="r0c6">31</td>
                </tr>
                <tr>
                    <td class="weekend in-range available" data-title="r1c0">1</td>
                    <td class="in-range available" data-title="r1c1">2</td>
                    <td class="in-range available" data-title="r1c2">3</td>
                    <td class="in-range available" data-title="r1c3">4</td>
                    <td class="in-range available" data-title="r1c4">5</td>
                    <td class="in-range available" data-title="r1c5">6</td>
                    <td class="weekend in-range available" data-title="r1c6">7</td>
                </tr>
                <tr>
                    <td class="weekend in-range available" data-title="r2c0">8</td>
                    <td class="in-range available" data-title="r2c1">9</td>
                    <td class="in-range available" data-title="r2c2">10</td>
                    <td class="in-range available" data-title="r2c3">11</td>
                    <td class="in-range available" data-title="r2c4">12</td>
                    <td class="in-range available" data-title="r2c5">13</td>
                    <td class="weekend in-range available" data-title="r2c6">14</td>
                </tr>
                <tr>
                    <td class="weekend in-range available" data-title="r3c0">15</td>
                    <td class="in-range available" data-title="r3c1">16</td>
                    <td class="in-range available" data-title="r3c2">17</td>
                    <td class="in-range available" data-title="r3c3">18</td>
                    <td class="in-range available" data-title="r3c4">19</td>
                    <td class="in-range available" data-title="r3c5">20</td>
                    <td class="weekend in-range available" data-title="r3c6">21</td>
                </tr>
                <tr>
                    <td class="weekend in-range available" data-title="r4c0">22</td>
                    <td class="in-range available" data-title="r4c1">23</td>
                    <td class="in-range available" data-title="r4c2">24</td>
                    <td class="in-range available" data-title="r4c3">25</td>
                    <td class="active end-date in-range available" data-title="r4c4">26</td>
                    <td class="available" data-title="r4c5">27</td>
                    <td class="weekend available" data-title="r4c6">28</td>
                </tr>
                <tr>
                    <td class="weekend available" data-title="r5c0">29</td>
                    <td class="available" data-title="r5c1">30</td>
                    <td class="off ends available" data-title="r5c2">1</td>
                    <td class="off ends available" data-title="r5c3">2</td>
                    <td class="off ends available" data-title="r5c4">3</td>
                    <td class="off ends available" data-title="r5c5">4</td>
                    <td class="weekend off ends available" data-title="r5c6">5</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected">10/05/2020 - 11/26/2020</span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr show-calendar opensright" style="top: 1524px; left: 1145px; right: auto; display: none;"><div class="ranges"></div><div class="drp-calendar left"><div class="calendar-table"><table class="table-condensed"><thead><tr><th class="prev available"><span></span></th><th colspan="5" class="month">Oct 2020</th><th></th></tr><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr><td class="weekend off ends available" data-title="r0c0">27</td><td class="off ends available" data-title="r0c1">28</td><td class="off ends available" data-title="r0c2">29</td><td class="off ends available" data-title="r0c3">30</td><td class="available" data-title="r0c4">1</td><td class="available" data-title="r0c5">2</td><td class="weekend available" data-title="r0c6">3</td></tr><tr><td class="weekend available" data-title="r1c0">4</td><td class="available" data-title="r1c1">5</td><td class="available" data-title="r1c2">6</td><td class="available" data-title="r1c3">7</td><td class="available" data-title="r1c4">8</td><td class="available" data-title="r1c5">9</td><td class="weekend available" data-title="r1c6">10</td></tr><tr><td class="weekend available" data-title="r2c0">11</td><td class="available" data-title="r2c1">12</td><td class="available" data-title="r2c2">13</td><td class="available" data-title="r2c3">14</td><td class="available" data-title="r2c4">15</td><td class="available" data-title="r2c5">16</td><td class="weekend available" data-title="r2c6">17</td></tr><tr><td class="today weekend active start-date active end-date available" data-title="r3c0">18</td><td class="available" data-title="r3c1">19</td><td class="available" data-title="r3c2">20</td><td class="available" data-title="r3c3">21</td><td class="available" data-title="r3c4">22</td><td class="available" data-title="r3c5">23</td><td class="weekend available" data-title="r3c6">24</td></tr><tr><td class="weekend available" data-title="r4c0">25</td><td class="available" data-title="r4c1">26</td><td class="available" data-title="r4c2">27</td><td class="available" data-title="r4c3">28</td><td class="available" data-title="r4c4">29</td><td class="available" data-title="r4c5">30</td><td class="weekend available" data-title="r4c6">31</td></tr><tr><td class="weekend off ends available" data-title="r5c0">1</td><td class="off ends available" data-title="r5c1">2</td><td class="off ends available" data-title="r5c2">3</td><td class="off ends available" data-title="r5c3">4</td><td class="off ends available" data-title="r5c4">5</td><td class="off ends available" data-title="r5c5">6</td><td class="weekend off ends available" data-title="r5c6">7</td></tr></tbody></table></div><div class="calendar-time"><select class="hourselect"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected="selected">12</option></select> : <select class="minuteselect"><option value="0" selected="selected">00</option><option value="30">30</option></select> <select class="ampmselect"><option value="AM" selected="selected">AM</option><option value="PM">PM</option></select></div></div><div class="drp-calendar right"><div class="calendar-table"><table class="table-condensed"><thead><tr><th></th><th colspan="5" class="month">Nov 2020</th><th class="next available"><span></span></th></tr><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr><td class="weekend off ends available" data-title="r0c0">25</td><td class="off ends available" data-title="r0c1">26</td><td class="off ends available" data-title="r0c2">27</td><td class="off ends available" data-title="r0c3">28</td><td class="off ends available" data-title="r0c4">29</td><td class="off ends available" data-title="r0c5">30</td><td class="weekend off ends available" data-title="r0c6">31</td></tr><tr><td class="weekend available" data-title="r1c0">1</td><td class="available" data-title="r1c1">2</td><td class="available" data-title="r1c2">3</td><td class="available" data-title="r1c3">4</td><td class="available" data-title="r1c4">5</td><td class="available" data-title="r1c5">6</td><td class="weekend available" data-title="r1c6">7</td></tr><tr><td class="weekend available" data-title="r2c0">8</td><td class="available" data-title="r2c1">9</td><td class="available" data-title="r2c2">10</td><td class="available" data-title="r2c3">11</td><td class="available" data-title="r2c4">12</td><td class="available" data-title="r2c5">13</td><td class="weekend available" data-title="r2c6">14</td></tr><tr><td class="weekend available" data-title="r3c0">15</td><td class="available" data-title="r3c1">16</td><td class="available" data-title="r3c2">17</td><td class="available" data-title="r3c3">18</td><td class="available" data-title="r3c4">19</td><td class="available" data-title="r3c5">20</td><td class="weekend available" data-title="r3c6">21</td></tr><tr><td class="weekend available" data-title="r4c0">22</td><td class="available" data-title="r4c1">23</td><td class="available" data-title="r4c2">24</td><td class="available" data-title="r4c3">25</td><td class="available" data-title="r4c4">26</td><td class="available" data-title="r4c5">27</td><td class="weekend available" data-title="r4c6">28</td></tr><tr><td class="weekend available" data-title="r5c0">29</td><td class="available" data-title="r5c1">30</td><td class="off ends available" data-title="r5c2">1</td><td class="off ends available" data-title="r5c3">2</td><td class="off ends available" data-title="r5c4">3</td><td class="off ends available" data-title="r5c5">4</td><td class="weekend off ends available" data-title="r5c6">5</td></tr></tbody></table></div><div class="calendar-time"><select class="hourselect"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11" selected="selected">11</option><option value="12">12</option></select> : <select class="minuteselect"><option value="0">00</option><option value="30">30</option></select> <select class="ampmselect"><option value="AM">AM</option><option value="PM" selected="selected">PM</option></select></div></div><div class="drp-buttons"><span class="drp-selected">10/18/2020 12:00 AM - 10/18/2020 11:59 PM</span><button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button><button class="applyBtn btn btn-sm btn-primary" type="button">Apply</button> </div></div>
<div class="daterangepicker ltr show-ranges opensright" style="display: none; top: 1610px; left: 1104px; right: auto;"><div class="ranges"><ul><li data-range-key="Today">Today</li><li data-range-key="Yesterday">Yesterday</li><li data-range-key="Last 7 Days">Last 7 Days</li><li data-range-key="Last 30 Days" class="active">Last 30 Days</li><li data-range-key="This Month">This Month</li><li data-range-key="Last Month">Last Month</li><li data-range-key="Custom Range">Custom Range</li></ul></div><div class="drp-calendar left"><div class="calendar-table"><table class="table-condensed"><thead><tr><th class="prev available"><span></span></th><th colspan="5" class="month">Sep 2020</th><th></th></tr><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr><td class="weekend off ends available" data-title="r0c0">30</td><td class="off ends available" data-title="r0c1">31</td><td class="available" data-title="r0c2">1</td><td class="available" data-title="r0c3">2</td><td class="available" data-title="r0c4">3</td><td class="available" data-title="r0c5">4</td><td class="weekend available" data-title="r0c6">5</td></tr><tr><td class="weekend available" data-title="r1c0">6</td><td class="available" data-title="r1c1">7</td><td class="available" data-title="r1c2">8</td><td class="available" data-title="r1c3">9</td><td class="available" data-title="r1c4">10</td><td class="available" data-title="r1c5">11</td><td class="weekend available" data-title="r1c6">12</td></tr><tr><td class="weekend available" data-title="r2c0">13</td><td class="available" data-title="r2c1">14</td><td class="available" data-title="r2c2">15</td><td class="available" data-title="r2c3">16</td><td class="available" data-title="r2c4">17</td><td class="available" data-title="r2c5">18</td><td class="weekend active start-date available" data-title="r2c6">19</td></tr><tr><td class="weekend in-range available" data-title="r3c0">20</td><td class="in-range available" data-title="r3c1">21</td><td class="in-range available" data-title="r3c2">22</td><td class="in-range available" data-title="r3c3">23</td><td class="in-range available" data-title="r3c4">24</td><td class="in-range available" data-title="r3c5">25</td><td class="weekend in-range available" data-title="r3c6">26</td></tr><tr><td class="weekend in-range available" data-title="r4c0">27</td><td class="in-range available" data-title="r4c1">28</td><td class="in-range available" data-title="r4c2">29</td><td class="in-range available" data-title="r4c3">30</td><td class="off ends in-range available" data-title="r4c4">1</td><td class="off ends in-range available" data-title="r4c5">2</td><td class="weekend off ends in-range available" data-title="r4c6">3</td></tr><tr><td class="weekend off ends in-range available" data-title="r5c0">4</td><td class="off ends in-range available" data-title="r5c1">5</td><td class="off ends in-range available" data-title="r5c2">6</td><td class="off ends in-range available" data-title="r5c3">7</td><td class="off ends in-range available" data-title="r5c4">8</td><td class="off ends in-range available" data-title="r5c5">9</td><td class="weekend off ends in-range available" data-title="r5c6">10</td></tr></tbody></table></div><div class="calendar-time" style="display: none;"></div></div><div class="drp-calendar right"><div class="calendar-table"><table class="table-condensed"><thead><tr><th></th><th colspan="5" class="month">Oct 2020</th><th class="next available"><span></span></th></tr><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr><td class="weekend off ends in-range available" data-title="r0c0">27</td><td class="off ends in-range available" data-title="r0c1">28</td><td class="off ends in-range available" data-title="r0c2">29</td><td class="off ends in-range available" data-title="r0c3">30</td><td class="in-range available" data-title="r0c4">1</td><td class="in-range available" data-title="r0c5">2</td><td class="weekend in-range available" data-title="r0c6">3</td></tr><tr><td class="weekend in-range available" data-title="r1c0">4</td><td class="in-range available" data-title="r1c1">5</td><td class="in-range available" data-title="r1c2">6</td><td class="in-range available" data-title="r1c3">7</td><td class="in-range available" data-title="r1c4">8</td><td class="in-range available" data-title="r1c5">9</td><td class="weekend in-range available" data-title="r1c6">10</td></tr><tr><td class="weekend in-range available" data-title="r2c0">11</td><td class="in-range available" data-title="r2c1">12</td><td class="in-range available" data-title="r2c2">13</td><td class="in-range available" data-title="r2c3">14</td><td class="in-range available" data-title="r2c4">15</td><td class="in-range available" data-title="r2c5">16</td><td class="weekend in-range available" data-title="r2c6">17</td></tr><tr><td class="today weekend active end-date in-range available" data-title="r3c0">18</td><td class="available" data-title="r3c1">19</td><td class="available" data-title="r3c2">20</td><td class="available" data-title="r3c3">21</td><td class="available" data-title="r3c4">22</td><td class="available" data-title="r3c5">23</td><td class="weekend available" data-title="r3c6">24</td></tr><tr><td class="weekend available" data-title="r4c0">25</td><td class="available" data-title="r4c1">26</td><td class="available" data-title="r4c2">27</td><td class="available" data-title="r4c3">28</td><td class="available" data-title="r4c4">29</td><td class="available" data-title="r4c5">30</td><td class="weekend available" data-title="r4c6">31</td></tr><tr><td class="weekend off ends available" data-title="r5c0">1</td><td class="off ends available" data-title="r5c1">2</td><td class="off ends available" data-title="r5c2">3</td><td class="off ends available" data-title="r5c3">4</td><td class="off ends available" data-title="r5c4">5</td><td class="off ends available" data-title="r5c5">6</td><td class="weekend off ends available" data-title="r5c6">7</td></tr></tbody></table></div><div class="calendar-time" style="display: none;"></div></div><div class="drp-buttons"><span class="drp-selected">09/19/2020 - 10/18/2020</span><button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button><button class="applyBtn btn btn-sm btn-primary" type="button">Apply</button> </div></div>
</body>
</html>
