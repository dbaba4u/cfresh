@extends('admin.layouts.master')
{{--@section('klass','active')--}}

@section('styles')
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <!-- DataTables -->
    {{--    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">--}}
    {{--    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">--}}
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-users"></i> Employees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                    <li><a href="#">Employees </i> </a> - </li>
                    <li class="active"> New Employee</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <hr style="border: solid 0.5px #a3a3a3">
@endsection


@section('content')
   <div class="container-fluid">
       <div class="col-md-6 m-auto">
           <form action="{{route('employee.query')}}" method="post">
               @csrf
               <div class="card">
                   <div class="card-header bg-danger">
                       <strong>Employees' Query</strong> Form
                   </div>
                   <div class="card-body">
                       <p class="lead text-center">
                           <small>Select Employee in question from the list and entered the query given to him on the field below ...</small>
                       </p>
                       <div class="form-group col-md-6">
                           <select name="employee_id" id="employee_id" class="form-control select2">
                               <option value="" selected disabled>Select Employee from the list</option>
                               @foreach($employees as $employee)
                                   <option value="{{$employee->id}}">{{$employee->name}}</option>
                               @endforeach
                           </select>
                       </div>
                       <div class="form-group col-md-12">
                           <textarea name="query" id="query" rows="8" class="form-control" placeholder="Write your query here ..."></textarea>
                       </div>
                   </div>
                   <div class="card-footer text-center">
                       <button type="submit" class="btn btn-dark form-control">Submit</button>
                   </div>
               </div>
           </form>
       </div>
   </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    {{--    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>--}}
    {{--    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>--}}
    {{--    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>--}}
    {{--    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>--}}

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });


            $("#users_list").DataTable({
                "responsive": true,
                "autoWidth": false,
            });





        })
    </script>

    <script src="{{asset('js/scripts.js')}}"></script>

@endsection
