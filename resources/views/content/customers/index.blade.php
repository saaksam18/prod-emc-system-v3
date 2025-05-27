@extends('layouts/contentNavbarLayout')

@section('title', 'Customers Information')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        <li class="breadcrumb-item active">Customers Information</li>
    </ol>
</nav>
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6 mb-3">
                <button type="button" class="btn btn-warning">
                    <a href="{{ route('export-customer') }}" style="color: white">Download Data</a>
                </button>
            </div>
            <div class="col-lg-6">
                <div class="d-flex justify-content-end gap-3">
                    <div>
                        <span class="badge bg-label-warning rounded-pill mt-1">Scooter: {{ $scooter }}</span>
                    </div>
                    <div>
                        <span class="badge bg-label-danger rounded-pill mt-1">Visa: {{ $visa }}</span>
                    </div>
                    <div>
                        <span class="badge bg-label-primary rounded-pill mt-1">Work Permit: {{ $wp }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Customers Information</h5>

                {{-- Filter --}}
                <form action="" method="GET">
                    <div class="ms-3 me-3">
                        <div class="row">
                            <label class="col-form-label">Filter</label>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Customer Name</span>
                                    <input name="CustomerName" class="form-control" list="customer_name" id="CustomerName" value="{{ Request::get('CustomerName') }}" placeholder="Type to search...">
                                    <datalist id="customer_name">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->CustomerName }}"> {{ $customer->CustomerName }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Nationality</span>
                                    <input name="nationality" class="form-control" list="nationality_list" id="nationality" value="{{ Request::get('nationality') }}" placeholder="Type to search...">
                                    <datalist id="nationality_list">
                                        @foreach ($countriesList as $country)
                                        <option value="{{ $country->nationality }}"> {{ $country->nationality }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Address</span>
                                    <input name="address" class="form-control" id="address" value="{{ Request::get('address') }}" placeholder="Type to search...">
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <select class="form-select" name="gender" id="gender">
                                        <option value="">-- Select Gender --</option>
                                        @foreach ($customer_gender as $gender)
                                            <option value="{{ $gender->gender }}" @if (Request::get('gender') == $gender->gender) selected @endif>{{ $gender->gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <button class="btn btn-warning">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- END Filter --}}
                <div class="ms-3 me-3">
                    {{-- Message --}}
                    @include('layouts.sections.messages')
                    {{-- End Message --}}
                    <label class="col-form-label">Table Data</label>
                </div>
                <div class="table-responsive text-nowrap">
                    @if (count($customers) > 0)
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        @sortablelink('customerID', 'No.')
                                    </th>
                                    <th>
                                        @sortablelink('CustomerName', 'Customer Name')
                                    </th>
                                    <th>
                                        @sortablelink('gender', 'Gender')
                                    </th>
                                    <th>
                                        @sortablelink('nationality', 'Nationality')
                                    </th>
                                    <th>
                                        @sortablelink('Contact')
                                    </th>
                                    <th>
                                        @sortablelink('address', 'Address')
                                    </th>
                                    <th>
                                        @sortablelink('comment', 'comment')
                                    </th>
                                    <th>
                                        @sortablelink('userID', 'Inputer')
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td class="text-center">
                                            <a href="{{ route('customers.show', $customer->customerID) }}">{{ $customer->customerID }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('customers.show', $customer->customerID) }}">{{ $customer->CustomerName }}</a>
                                        </td>

                                        @if ($customer->gender == null)
                                            <td>No Data</td>
                                        @else
                                            <td>{{ $customer->gender }}</td>
                                        @endif

                                        @if ($customer->nationality == null)
                                            <td>No Data</td>
                                        @else
                                            <td>{{ $customer->nationality }}</td>
                                        @endif

                                        <td>
                                            @foreach ($customer_contacts as $customer_contact)
                                                @if ($customer_contact->customerID == $customer->customerID)
                                                    <a href="{{ route('contacts.edit', $customer_contact->customerID) }}">
                                                        <li>
                                                            {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                                        </li>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </td>

                                        @if ($customer->address != null)
                                            <td>{{ $customer->address }}</td>
                                        @else
                                            <td>No Data</td>
                                        @endif

                                        @if ($customer->address == null)
                                            <td>No Data</td>
                                        @else
                                            <td>{{ $customer->address }}</td>
                                        @endif

                                        <td>
                                                {{ $customer->user->name }}
                                        </td>
                                        <td class="text-center">
                                            {!! Form::open(['method' => 'get','route' => ['customers.edit', $customer->customerID],'style'=>'display:inline']) !!}
                                                {!! Form::submit('Edit', ['class' => 'btn btn-primary btn-xs']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <table class="table table-bordered text-nowrap mb-3">
                            <thead>
                                <tr>
                                    <th>
                                        @sortablelink('customerID', 'No.')
                                    </th>
                                    <th>
                                        @sortablelink('CustomerName', 'Customer Name')
                                    </th>
                                    <th>
                                        @sortablelink('gender', 'Gender')
                                    </th>
                                    <th>
                                        @sortablelink('nationality', 'Nationality')
                                    </th>
                                    <th>
                                        @sortablelink('address', 'Address')
                                    </th>
                                    <th>
                                        @sortablelink('comment', 'comment')
                                    </th>
                                    <th>
                                        @sortablelink('userID', 'Inputer')
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                        <p class="text-center">No customers found.</p>
                    @endif
                </div>
                <!-- Basic Pagination -->
                <div class="demo-inline-spacing">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            @if ($customers->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="customers?page={{ $customers->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                                @endif

                                    @for ($i = 1; $i <= $customers->lastPage(); $i++)
                                        <li class="page-item {{ $customers->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="customers?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                @if ($customers->currentPage() < $customers->lastPage())
                                    <li class="page-item last">
                                        <a href="customers?page={{ $customers->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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

<script>
    window.onbeforeunload = function() {
    localStorage.setItem('scrollPos', document.documentElement.scrollTop);
    };

    window.onload = function() {
    var scrollPos = localStorage.getItem('scrollPos');
    if (scrollPos) {
        window.scrollTo(0, scrollPos);
    }
    };

</script>

@endsection