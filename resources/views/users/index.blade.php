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
      <li class="breadcrumb-item active">User Management</li>
  </ol>
</nav>

<div class="row mb-3">
    <div class="col-sm-10">
        <button type="button" class="btn btn-warning">
            <a href="{{ route('users.create') }}" style="color: white">Add New User</a>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
        <h5 class="card-header bg-primary text-white">Users Management</h5>
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
                @if (count($data) > 0)
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
                                    @sortablelink('email', 'Email')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                            <tr>
                                <td class="d-flex justify-content-between gap-1 text-nowrap">
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you want to delete user account name {{ $user->name }} ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
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
                                    @sortablelink('email', 'Email')
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <p class="text-center">No users found.</p>
                @endif
            </div>
            <!-- Basic Pagination -->
            <div class="demo-inline-spacing">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @if ($data->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="users?page={{ $data->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                            @endif

                                @for ($i = 1; $i <= $data->lastPage(); $i++)
                                    <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="users?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($data->currentPage() < $data->lastPage())
                                <li class="page-item last">
                                    <a href="users?page={{ $data->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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
</div>

{!! $data->render() !!}
@endsection