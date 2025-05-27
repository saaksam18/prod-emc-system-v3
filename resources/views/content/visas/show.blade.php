@extends('layouts/contentNavbarLayout')

@section('title', 'Visa Customer Detail')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('customers.index') }}">Visa Customer</a>
        </li>
        <li class="breadcrumb-item active">Visa Customers Details</li>
    </ol>
</nav>
{{-- End Breadcrumb --}}

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Visa Customers Details</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        {{-- Basic Data --}}
                        <tr>
                            <th class="bg-primary text-white fw-bold">Customer Informations</th>
                            <th class="fw-bold">Customer Name</th>
                            @foreach ($visas as $visa)
                                <th class="fw-bold">
                                    <a href="{{ route('visas.edit', $visa->visaID) }}">{{ $visa->customer->CustomerName }}</a>
                                </th>
                                <td colspan="2">
                                    @if ($visa->customer->nationality != null)
                                        <tr>
                                            <td>
                                                <th>Nationality</th>
                                                <td>
                                                    {{ $visa->customer->nationality }}
                                                </td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($visa->customer->gender != null)
                                        <tr>
                                            <td>
                                                <th>Gender</th>
                                                <td>
                                                    {{ $visa->customer->gender }}
                                                </td>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <th>Contact</th>
                                            <td>
                                                @foreach ($customer_contacts as $customer_contact)
                                                    @if ($customer_contact->customerID == $visa->customer->customerID)
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
                        @if (count($visas) > 0)
                        <tr>
                            <th class="bg-dark text-white fw-bold">Visa Informations</th>
                            @foreach ($visas as $visa)
                            <th class="fw-bold">Status</th>
                                @if ($visa->is_Active == 1)
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
                                        <th>Visa No.</th>
                                        <td>
                                            {{ $visa->visaID }}
                                        </td>
                                    </td>
                                </tr>
                                @if ($visa->visaType > 0)
                                    <tr>
                                        <td>
                                            <th>Type</th>
                                            <td>
                                                {{ $visa->visaType }}
                                            </td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($visa->amount > 0)
                                    <tr>
                                        <td>
                                            <th>Passport Amount</th>
                                            <td>
                                                {{ $visa->amount }}
                                            </td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($visa->expireDate != null)
                                    <tr>
                                        <td>
                                            <th>Expire Date</th>
                                            <td>
                                                {{ date('d-M-Y', strtotime($visa->expireDate)) }}
                                            </td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($visa->expireDate != null)
                                    <tr>
                                        <td>
                                            <th>Incharge Staff</th>
                                            <td>
                                                @foreach ($users as $user)
                                                    @if ($visa->staff_id == $user->id)
                                                        {{ $user->name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($visa->expireDate != null)
                                    <tr>
                                        <td>
                                            <th>Input Date</th>
                                            <td>
                                                {{ date('d-M-Y', strtotime($visa->created_at)) }}
                                            </td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($visa->userID != null)
                                    <tr>
                                        <td>
                                            <th>Inputer</th>
                                            <td>
                                                {{ $visa->user->name }}
                                            </td>
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

@if (count($visas) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Visa Extension Logs</h5>
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
                                <span class="input-group-text">Visa Type</span>
                                <input class="form-control" type="text" name="visaType" id="visaType" value="{{ Request::get('visaType') }}">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Expire Date</span>
                                <input class="form-control" type="date" name="expireDate" id="expireDate" value="{{ Request::get('expireDate') }}">
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
                @if (count($visa_logs) > 0)
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
                    <tbody>
                        @foreach ($visa_logs as $visa)
                        @if ($visa->is_Active > 0)
                            <tr>
                        @else
                            <tr class="table-danger">
                        @endif
                                <td>
                                    {{ $visa->customer->CustomerName }}
                                </td>
                                @if ($visa->is_Active == 0)
                                    <td>
                                        <span class="badge bg-label-danger">Reminded</span>
                                    </td>
                                @else
                                <td>
                                    <span class="badge bg-label-warning">Remind Date Coming</span>
                                </td>
                                @endif
                                <td>{{ $visa->visaType }}</td>
                                <td>{{ date('d-M-Y', strtotime($visa->expireDate)) }}</td>
                                @foreach ($users as $user)
                                    @if ($visa->staff_id == $user->id)
                                        <td>{{ $user->name }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $visa->user->name }}</td>
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
                        @if ($visa_logs->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="/visas/{{ $visa->visaID }}?page={{ $visa_logs->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                            @endif

                                @for ($i = 1; $i <= $visa_logs->lastPage(); $i++)
                                    <li class="page-item {{ $visa_logs->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="/visas/{{ $visa->visaID }}?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($visa_logs->currentPage() < $visa_logs->lastPage())
                                <li class="page-item last">
                                    <a href="/visas/{{ $visa->visaID }}?page={{ $visa_logs->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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