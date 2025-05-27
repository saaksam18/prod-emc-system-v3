@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Role')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles.index') }}">Role Management</a>
        </li>
        <li class="breadcrumb-item active">Edit Role</li>
    </ol>
</nav>

<div class="col-lg-12">
    <div class="card mb-4">
        <div class="card-body">
            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
            <div class="row mb-3">
                <div class="row mb-3">
                    <label class="form-label" for="basic-default-name">Name <span class="text-danger">*</span></label>
                    <div class="form-group">
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="form-label" for="basic-default-name">Permission <span class="text-danger">*</span></label>
                    <div class="input-group">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">No.</th>
                                <th style="width: 500px">Dashboard</th>
                                <th>View</th>
                                <th>Create</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Operation</td>
                                @foreach($permissions_op as $value)
                                    <td>
                                    <!-- checkbox -->
                                        <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}</label>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>Role</td>
                                @foreach($permissions_role as $value)
                                    <td>
                                    <!-- checkbox -->
                                        <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}</label>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>User</td>
                                @foreach($permissions_user as $value)
                                    <td>
                                    <!-- checkbox -->
                                        <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}</label>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td>Dashboard</td>
                                @foreach($permission_dashboard as $value)
                                    <td colspan="4">
                                    <!-- checkbox -->
                                        <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}</label>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                            <td class="text-center">5</td>
                                <td>All Report</td>
                                @foreach($permission_report as $value)
                                    <td colspan="4">
                                    <!-- checkbox -->
                                        <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}</label>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
</div>
@endsection