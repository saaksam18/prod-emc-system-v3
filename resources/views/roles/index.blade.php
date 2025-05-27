@extends('layouts/contentNavbarLayout')

@section('title', 'Role Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap-select-country.js') }}"></script>
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Role Management</li>
    </ol>
</nav>
    
<div class="row mb-3">
    <div class="col-lg-12">
        <button type="button" class="btn btn-warning">
            @can('role-create')
                <a class="text-white" href="{{ route('roles.create') }}"> Create New Role</a>
            @endcan
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Roles Management</h5>
            <div class="table-responsive text-nowrap">
                <div class="table-responsive text-nowrap">
                    <div class="ms-3 me-3">
                        {{-- Message --}}
                        <div class="mt-3">
                            @include('layouts.sections.messages')
                        </div>
                        {{-- End Message --}}
                        <label class="col-form-label">Table Data</label>
                    </div>
                    @if (count($roles) > 0)
                        <table class="table table-hover table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 100px">Actions</th>
                                    <th>
                                        @sortablelink('id', 'No.')
                                    </th>
                                    <th>
                                        @sortablelink('name', 'Name')
                                    </th>
                                    <th>
                                        @sortablelink('guard_name', 'Guard Name')
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td class="d-flex justify-content-between gap-1 text-nowrap">
                                        <a class="btn btn-sm btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" onsubmit="return confirm('Are you want to delete role {{ $role->name }} ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->guard_name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <table class="table table-bordered text-nowrap mb-3">
                            <thead>
                                <tr>
                                    <th style="width: 100px">Actions</th>
                                    <th>
                                        @sortablelink('id', 'No.')
                                    </th>
                                    <th>
                                        @sortablelink('name', 'Name')
                                    </th>
                                    <th>
                                        @sortablelink('guard_name', 'Guard Name')
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <p class="text-center">No roles found.</p>
                    @endif
                </div>
                <!-- Basic Pagination -->
                <div class="demo-inline-spacing">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            @if ($roles->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="roles?page={{ $roles->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                                @endif
    
                                    @for ($i = 1; $i <= $roles->lastPage(); $i++)
                                        <li class="page-item {{ $roles->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="roles?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor
    
                                @if ($roles->currentPage() < $roles->lastPage())
                                    <li class="page-item last">
                                        <a href="roles?page={{ $roles->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                    </li>
                                @endif
                        </ul>
                    </nav>
                </div>
                <!--/ Basic Pagination -->
            </div>
        </div>
    </div>
</div>

{!! $roles->render() !!}
@endsection