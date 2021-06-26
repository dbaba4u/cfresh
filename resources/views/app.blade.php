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

    <script src="{{asset('js/app.js')}}" defer></script>
{{--    <script src="{{asset('js/all.js')}}" defer></script>--}}
{{--    <script src="{{asset('plugins/moment/moment.min.js')}}" defer></script>--}}

    @yield('head')
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div id="app">
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

                <index></index>
                {{--            @yield('content')--}}

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">

            <strong>Copyright &copy; 2020-2021 <a href="{{route('home')}}">cfresh.org</a>.</strong> All rights
            reserved.
        </footer>

    </div>
    <!-- ./wrapper -->

    @yield('scripts')
</div>



{{--<script src="{{asset('frontend/js/jquery-validation/dist/jquery.validate.min.js')}}"></script>--}}
{{--<script src="{{ asset('js/toastr.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}


{{--<script>
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
</script>--}}
</body>
</html>
