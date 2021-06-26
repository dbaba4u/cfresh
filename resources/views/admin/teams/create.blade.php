@extends('admin.layouts.master')

@section('styles')

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
                        <h5 class="card-title"><strong>New Team</strong></h5>
                        <div class="ml-auto">
                            <a href="{{route('teams.index')}}" class="btn btn-sm btn-outline-light">Back to all Teams</a>
                        </div>
                    </div>
                    <form action="{{route('teams.store')}}" method="post">
                        @csrf
                        <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
    {{--                                        <label for="employee">Employee Name</label>--}}
                                            <select name="employee_id" id="employee" class="form-control employee {{$errors->has('employee_id') ? 'is-invalid' : ''}}" aria-describedby="employeeFeedback" data-width="100%">
                                                <option value="0" selected disabled>Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('employee_id'))
                                                <div class="invalid-feedback"><span>{{$errors->first('employee_id')}}</span></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" value="{{old('name')}}" name="name" placeholder="Team name">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback"><span>{{$errors->first('name')}}</span></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="unit" id="unit" class="form-control unit {{$errors->has('unit') ? 'is-invalid' : ''}}" data-width="100%">
                                                <option value="0" selected disabled>Select Unit</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Blower' : $employee->unit )}} " >Blower</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Machine Filling' : $employee->unit )}} ">Machine Filling</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Manual Filling' : $employee->unit )}} ">Manual Filling</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Labelers' : $employee->unit )}} ">Labelers</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Arrangers' : $employee->unit )}} ">Arrangers</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Shrink Wrap 1' : $employee->unit )}} ">Shrink Wrap 1</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Shrink Wrap 2' : $employee->unit )}} ">Shrink Wrap 2</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Pickers' : $employee->unit )}} ">Pickers</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Supervisor' : $employee->unit )}} ">Supervisor</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Security' : $employee->unit )}} ">Security</option>
                                                <option value="{{old('unit', empty($employee->unit) ? 'Reserve' : $employee->unit )}} ">Reserve</option>
                                            </select>
                                            @if($errors->has('unit'))
                                                <div class="invalid-feedback"><span>{{$errors->first('unit')}}</span></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-lg btn-outline-primary"> Create Team </button>
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




@endsection
