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
                <h1><i class="fa fa-wrench"></i> Teams Setup </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                    <li><a href="#">Teams </i> </a> - </li>
                    <li class="active">Teams Setup</li>
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
                        <h5 class="card-title"><strong>All Teams</strong></h5>
                        <div class="ml-auto">
                            <a href="{{route('teams.create')}}" class="btn btn-sm btn-outline-light">Create New Team</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="team_table">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Team Name</th>
                                        <th>Employee Name</th>
                                        <th>Unit</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($teams as $team)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$team->name}}</td>
                                        <td>{{\App\Employee::where('id',$team->employee_id)->first()->name}}</td>
                                        <td>{{$team->unit}}</td>
                                        <td class="btn-group">
{{--                                        @can ('update', $question)--}}
                                            <a href="{{route('teams.edit',$team->id)}}" class="btn btn-sm btn-outline-primary">Edit</a>
{{--                                        @endcan--}}
{{--                                        @can ('delete', $question)--}}
                                            <form action="{{route('teams.destroy', $team->id)}}" method="post">
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
            $("#team_table").DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });


    </script>





@endsection
