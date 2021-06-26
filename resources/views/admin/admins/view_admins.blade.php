@extends('admin.layouts.master')
{{--@section('klass','active')--}}

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
@endsection

@section('page-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-user-cog"></i> Admins Management </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-tachometer-alt"></i> Home </a> - </li>
                    <li><a href="#">Admins </i> </a> - </li>
                    <li class="active"> View Admins</li>
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
                @include('admin.includes.alert-msg')
                <div class="card">
                    <div class="card-header p-2 bg-gradient-info">

                        <h5 class="card-title"><i class="fa fa-users"></i> Admins</h5>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="view_admins" class="table table-striped table-bordered table-sm" style="font-size: 0.8rem">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Type</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Updated On</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $admin)
                                    <?php if($admin->type == 'Admin')
                                    {
                                        $roles = 'All';
                                    }else
                                    {
                                        $roles = '';
                                        if ($admin->employees_access!=0)
                                        {
                                            $roles .= 'Categories, ';
                                        }
                                        if ($admin->products_access!=0)
                                        {
                                            $roles .= 'Products, ';
                                        }
                                        if ($admin->orders_access!=0)
                                        {
                                            $roles .= 'Orders, ';
                                        }
                                        if ($admin->users_access!=0)
                                        {
                                            $roles .= 'Customers, ';
                                        }

	 									if ($admin->finance_access!=0)
                                        {
                                            $roles .= 'Finance, ';
                                        }
                                        if ($admin->operation_access!=0)
                                        {
                                            $roles .= 'Operations, ';
                                        }


                                        if ($admin->inventories_view_access!=0 ||$admin->inventories_manage_access!=0)
                                        {
                                            $roles .= 'inventories, ';
                                        }
                                        if ($admin->store_move_access!=0 ||$admin->store_view_access!=0)
                                        {
                                            $roles .= 'Store, ';
                                        }

                                    }
                                    ?>
                                    <tr>
                                        <td>{{$admin->id}}</td>
                                        <td>{{!empty($admin->employee) ? $admin->employee->name : ''}}</td>
                                        <td>{{$admin->username}}</td>
                                        <td>{{$admin->type}}</td>
                                        <td>{{$roles}}</td>
                                        <td>
                                            @if($admin->status == 1)
                                                <span style="color: green">Active</span>
                                            @else
                                                <span style="color: red">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{\Carbon\Carbon::parse($admin->created_at)->toFormattedDateString()}} - {{\Carbon\Carbon::parse($admin->created_at)->format('h:i A')}}</td>
                                        <td>{{\Carbon\Carbon::parse($admin->updated_at)->toFormattedDateString()}} - {{\Carbon\Carbon::parse($admin->updated_at)->format('h:i A')}}</td>
{{--                                        <td>--}}
{{--                                            <a href="{{route('editAdmin', ['id'=>$admin->id])}}" class="btn btn-primary btn-sm">Edit</a>--}}
{{--                                        </td>--}}
                                        <td class="text-center">
                                            <div class="row justify-content-center " style="margin: 0">
{{--                                                @can('role-edit')--}}
                                                    <a href="{{route('editAdmin', ['id'=>$admin->id])}}" role="button" style="margin-top: 8px; "> <i class="fa fa-pen text-success"></i> </a>
{{--                                                @endcan--}}
{{--                                                @can('role-delete')--}}
                                                    <form action="{{route('admins.destroy', $admin->id )}}" method="POST" >
                                                        {{method_field('DELETE')}}
                                                        @csrf
                                                        <button rel="{{$admin->id}}" type="submit" role="button" class="btn btn-sm delete" style="color: darkred"> <i class="fa fa-trash text-danger"></i> </button>
                                                    </form>
{{--                                                @endcan--}}
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>

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

    <script>
        $(function () {
            $("#view_admins").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        })
    </script>

    <script src="{{asset('js/scripts.js')}}"></script>

   {{-- <script>
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            var id = $(this).attr('rel');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    Swal.fire(
                        "Deleted!",
                        "Your file has been deleted.",
                        "success",

                    );
                    $.ajax({
                        url: '/admins/delete/'+id,
                        method: 'DELETE',
                        success: function(response) {
                            console.log(response)
                        }
                    });
                    location.reload()
                    // window.location.href = '/employeeCategories/'+id;
                    // result.dismiss can be "cancel", "overlay",
                    // "close", and "timer"
                } else if (result.dismiss === "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Your imaginary file is safe :)",
                        "error"
                    )
                }
            });


        });
    </script>--}}
@endsection
