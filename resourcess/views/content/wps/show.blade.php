@extends('layouts/contentNavbarLayout')

@section('title', 'WP Customer Detail')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('customers.index') }}">Work Permit Customer</a>
        </li>
        <li class="breadcrumb-item active">WP Customers Details</li>
    </ol>
</nav>
{{-- End Breadcrumb --}}

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">WP Customers Details</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        {{-- Basic Data --}}
                        <tr>
                            <th class="bg-primary text-white fw-bold">Customer Informations</th>
                            <th class="fw-bold">Customer Name</th>
                            @foreach ($wp_logs as $wp)
                                <th class="fw-bold">
                                    <a href="{{ route('work-permit.edit', $wp->wpID) }}">{{ $wp->customer->CustomerName }}</a>
                                </th>
                                <td colspan="2">
                                    @if ($wp->customer->nationality != null)
                                        <tr>
                                            <td>
                                                <th class="fw-bold">Nationality</th>
                                                <th class="fw-bold">
                                                    {{ $wp->customer->nationality }}
                                                </th>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($wp->customer->gender != null)
                                        <tr>
                                            <td>
                                                <th class="fw-bold">Gender</th>
                                                <th class="fw-bold">
                                                    {{ $wp->customer->gender }}
                                                </th>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <th>Contact</th>
                                            <td>
                                                @foreach ($customer_contacts as $customer_contact)
                                                    @if ($customer_contact->customerID == $wp->customer->customerID)
                                                        <li>
                                                            {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </td>
                                    </tr>
                                </td>
                            @endforeach
                        </tr>
                        {{-- End Basic Data --}}

                        {{-- Visa Data --}}
                        @if (count($wps) > 0)
                        <tr>
                            <th class="bg-dark text-white fw-bold">Visa Informations</th>
                            @foreach ($wps as $wp)
                            <th class="fw-bold">Status</th>
                                @if ($wp->is_Active == 1)
                                    <td class="fw-bold">
                                        <span class="badge bg-warning text-white">Remind Date Coming</span>
                                    </td>
                                @else
                                    <td class="fw-bold">
                                        <span class="badge bg-danger text-white">Reminded</span>
                                    </td>
                                @endif
                            <td>
                                <tr>
                                    <td>
                                        <th class="fw-bold">Work Permit No.</th>
                                        <th class="fw-bold">
                                            {{ $wp->wpID }}
                                        </th>
                                    </td>
                                </tr>
                                @if ($wp->wpExpireDate != null)
                                    <tr>
                                        <td>
                                            <th class="fw-bold">Expire Date</th>
                                            <th class="fw-bold">
                                                {{ date('d-M-Y', strtotime($wp->wpExpireDate)) }}
                                            </th>
                                        </td>
                                    </tr>
                                @endif
                                @if ($wp->staff_id != null)
                                    <tr>
                                        <td>
                                            <th class="fw-bold">Incharge Staff</th>
                                            <th class="fw-bold">
                                                @foreach ($users as $user)
                                                    @if ($wp->staff_id == $user->id)
                                                        {{ $user->name }}
                                                    @endif
                                                @endforeach
                                            </th>
                                        </td>
                                    </tr>
                                @endif
                                @if ($wp->userID != null)
                                    <tr>
                                        <td>
                                            <th class="fw-bold">Inputer</th>
                                            <th class="fw-bold">
                                                {{ $wp->user->name }}
                                            </th>
                                        </td>
                                    </tr>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        {{-- End Visa Data --}}
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@if (count($wps) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Work Permit Logs</h5>
            {{-- Filter --}}
            <form action="" method="GET">
                <div class="ms-3 me-3">
                    <div class="row">
                        <label class="col-form-label">Filter</label>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Status</span>
                                <select class="form-select" name="status" id="status">
                                    <option value="">-- Status --</option>
                                    @foreach ($statuss as $status)
                                        @if ($status->is_Active == 1)
                                            <option value="1" @if (Request::get('status') == 1) selected @endif>Remind Date Coming</option>
                                        @else
                                            <option value="0">Reminded</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Expire Date</span>
                                <input class="form-control" type="date" name="wpExpireDate" id="wpExpireDate" value="{{ Request::get('wpExpireDate') }}">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Incharger</span>
                                <select class="form-select" name="staff_id" id="staff_id">
                                    <option value="">-- Name --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (Request::get('staff_id') == $user->id) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <button class="btn btn-warning">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{-- End Filter --}}
            <div class="ms-3 me-3">
                <label class="col-lg-12 col-form-label">Table Data</label>
            </div>
            <div class="table-responsive text-nowrap">
                @if (count($wp_logs) > 0)
                <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('is_Active', 'Status')
                            </th>
                            <th>
                                @sortablelink('wpExpireDate', 'Expire Date')
                            </th>
                            <th>
                                @sortablelink('staff_id', 'Incharger')
                            </th>
                            <th>
                                @sortablelink('userID', 'Inputer')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wp_logs as $wp)
                        @if ($wp->is_Active > 0)
                            <tr>
                        @else
                            <tr class="table-danger">
                        @endif
                                <td>
                                    {{ $wp->customer->CustomerName }}
                                </td>
                                @if ($wp->is_Active == 0)
                                    <td>
                                        <span class="badge bg-label-danger">Reminded</span>
                                    </td>
                                @else
                                <td>
                                    <span class="badge bg-label-warning">Remind Date Coming</span>
                                </td>
                                @endif
                                <td>{{ date('d-M-Y', strtotime($wp->wpExpireDate)) }}</td>
                                @foreach ($users as $user)
                                    @if ($wp->staff_id == $user->id)
                                        <td>{{ $user->name }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $wp->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <table class="table table-hover table-bordered text-nowrap mb-3">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('is_Active', 'Status')
                            </th>
                            <th>
                                @sortablelink('visaType', 'Vias Type')
                            </th>
                            <th>
                                @sortablelink('expireDate', 'Expire Date')
                            </th>
                            <th>
                                @sortablelink('staff_id', 'Incharger')
                            </th>
                            <th>
                                @sortablelink('userID', 'Inputer')
                            </th>
                        </tr>
                    </thead>
                </table>
                <p class="text-center">No motorbikes found.</p>

            @endif
            </div>
            <!-- Basic Pagination -->
            <div class="demo-inline-spacing">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @if ($wp_logs->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="/wps/{{ $wp->wpID }}?page={{ $wp_logs->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                            @endif

                                @for ($i = 1; $i <= $wp_logs->lastPage(); $i++)
                                    <li class="page-item {{ $wp_logs->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="/wps/{{ $wp->wpID }}?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($wp_logs->currentPage() < $wp_logs->lastPage())
                                <li class="page-item last">
                                    <a href="/wps/{{ $wp->wpID }}?page={{ $wp_logs->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                </li>
                            @endif
                    </ul>
                </nav>
            </div>
            <!--/ Basic Pagination -->
        </div>
    </div>
</div>
@endif

@endsection