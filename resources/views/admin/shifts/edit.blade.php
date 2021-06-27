@extends('admin.layouts.master')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

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
                    <li><a href="#">Shifts </a> - </li>
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
                        <h5 class="card-title"><strong>Edit Shift</strong></h5>
                        <div class="ml-auto">
                            <a href="{{route('shift.index')}}" class="btn btn-sm btn-outline-light">Back to all Shifts</a>
                        </div>
                    </div>
                    <form action="{{route('shift.update', $shift->id)}}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" value="{{$shift->name}}" name="name" placeholder="Shift name">
                                        @if($errors->has('name'))
                                            <div class="invalid-feedback"><span>{{$errors->first('name')}}</span></div>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="team_id" id="team" class="form-control team {{$errors->has('team_id') ? 'is-invalid' : ''}}" aria-describedby="teamFeedback" data-width="100%">
                                            <option value="0" selected disabled>Select a team</option>
                                            @foreach($teams as $team)
                                                <option value="{{$team->id}}"
                                                {{$shift->team_id === $team->id ? 'selected' : ''}}
                                                >{{$team->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('team_id'))
                                            <div class="invalid-feedback"><span>{{$errors->first('team_id')}}</span></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Start Time:</label>
                                        <div class="input-group date" id="start_time" data-target-input="nearest">
                                            <input type="time" name="start_time" class="form-control datetimepicker-input {{$errors->has('start_time') ? 'is-invalid' : ''}}" value="{{$shift->start_time}}"  id="startTime" data-target="#start_time"/>
                                            @if($errors->has('start_time'))
                                                <div class="invalid-feedback"><span>{{$errors->first('start_time')}}</span></div>
                                            @endif
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label>Break Time:</label>
                                            <div class="input-group date" id="break_from" data-target-input="nearest">
                                                <div class="input-group-prepend" data-target="#break_from" data-toggle="datetimepicker">
                                                    <div class="input-group-text">From</div>
                                                </div>
                                                <input type="time" name="break_from" class="form-control datetimepicker-input {{$errors->has('break_from') ? 'is-invalid' : ''}}" value="{{$shift->break_from}}"   data-target="#break_from"/>
                                                @if($errors->has('break_from'))
                                                    <div class="invalid-feedback"><span>{{$errors->first('break_from')}}</span></div>
                                                @endif
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                        <div class="form-group">
                                            <label>-</label>
                                            <div class="input-group date" id="break_to" data-target-input="nearest">
                                                <div class="input-group-prepend" data-target="#break_to" data-toggle="datetimepicker">
                                                    <div class="input-group-text">To</div>
                                                </div>
                                                <input type="time" name="break_to" class="form-control datetimepicker-input {{$errors->has('break_to') ? 'is-invalid' : ''}}" value="{{$shift->break_to}}"  data-target="#break_to"/>
                                                @if($errors->has('break_to'))
                                                    <div class="invalid-feedback"><span>{{$errors->first('break_to')}}</span></div>
                                                @endif
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Close Time:</label>
                                        <div class="input-group date" id="close_time" data-target-input="nearest">
                                            <input type="time" name="close_time" class="form-control datetimepicker-input {{$errors->has('close_time') ? 'is-invalid' : ''}}" value="{{$shift->close_time}}" data-target="#close_time"/>
                                            @if($errors->has('close_time'))
                                                <div class="invalid-feedback"><span>{{$errors->first('close_time')}}</span></div>
                                            @endif
                                        </div>
                                        <!-- /.input group -->
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-lg btn-outline-primary"> Update Shift </button>
                            </div>
                        </div>
                    </form>
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
{{--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
            $('.employee').select2({
                placeholder: {
                    id: '0',
                    text: 'Select an Employee'
                },
                allowClear: true,
                theme: "classic"
            });

            $('.unit').select2({
                placeholder: {
                    id: '0',
                    text: 'Select a Unit'
                },
                allowClear: true,
                theme: "classic"
            });

        });
    </script>
    <script>
        // $(function() {
            $('#start_time').datetimepicker({
                format: 'hh:mm:ss',
            });


        // });
    </script>



@endsection
