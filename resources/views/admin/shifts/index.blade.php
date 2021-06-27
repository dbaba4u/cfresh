@extends('admin.layouts.master')

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-wrench"></i> Shift Management </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                    <li><a href="#">Shifts </i> </a> - </li>
                    <li class="active">Shift Management</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <hr style="border: solid 0.5px #a3a3a3">
@endsection


@section('content')
    <div class="container">

        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary d-flex align-items-center">
                        <h5 class="card-title"><strong>All Shifts</strong></h5>
                        <div class="ml-auto">
                            <a href="{{route('shift.create')}}" class="btn btn-sm btn-outline-light">Create New Shift</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="shift_table">
                                <thead class="bg-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Shift</th>
                                    <th>Team</th>
                                    <th>Start Time</th>
                                    <th>Break Time</th>
                                    <th>Close Time</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shifts as $shift)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$shift->name}}</td>
                                        <td>{{$shift->team->name}}</td>
                                        <td>{{\Carbon\Carbon::parse($shift->start_time)->format('h:i A')}}</td>
                                        <td>{{'From ' . \Carbon\Carbon::parse($shift->break_from)->format('h:i A')  .
                                        ' To ' . \Carbon\Carbon::parse($shift->break_to)->format('h:i A') }}</td>
                                        <td>{{\Carbon\Carbon::parse($shift->close_time)->format('h:i A') }}</td>
                                        <td class="btn-group">
                                            {{--                                        @can ('update', $question)--}}
                                            <a href="{{route('shift.edit',$shift->id)}}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            {{--                                        @endcan--}}
                                            {{--                                        @can ('delete', $question)--}}
                                            <form action="{{route('shift.destroy', $shift->id)}}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button class="q-delete btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                            {{--                                        @endcan--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <br/>

            </div>

        </div>
        <br/>
        <br/>

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
            $("#shift_table").DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });


    </script>





@endsection
