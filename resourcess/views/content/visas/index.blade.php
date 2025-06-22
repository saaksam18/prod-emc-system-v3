@extends('layouts/contentNavbarLayout')

@section('title', 'Visa Customer')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">All Visa Customers</li>
    </ol>
  </nav>
  <div class="row mb-3">
    <div class="col-sm-10">
      <button type="button" class="btn btn-warning">
        <a href="{{ route('visas.create') }}" style="color: white">Add Visa Customers</a>
      </button>
    </div>
  </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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
                                    <select class="form-select" name="contactType" id="contactType">
                                        <option value="">Contact Type</option>
                                        @foreach ($customer_contact_type as $customer_contact_ty)
                                            <option value="{{ $customer_contact_ty->contactType }}" @if (Request::get('contactType') == $customer_contact_ty->contactType) selected @endif>{{ $customer_contact_ty->contactType }}</option>
                                        @endforeach
                                    </select>
                                    <input name="contactDetail" class="form-control" list="contactD_list" id="contactDetail" value="{{ Request::get('contactDetail') }}" placeholder="Type to search...">
                                    <datalist id="contactD_list">
                                        @foreach ($customer_contact as $rental)
                                            <option value="{{ $rental->contactDetail }}"> {{ $rental->contactDetail }} </option>
                                        @endforeach
                                    </datalist>
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
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <select class="form-select" name="visaType" id="visaType">
                                        <option value="">-- Visa Type --</option>
                                        @foreach ($visa_type as $type)
                                            <option value="{{ $type->visaType }}" @if (Request::get('visaType') == $type->visaType) selected @endif>{{ $type->visaType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Expiration Date</span>
                                    <input type="date" name="expireDate" class="form-control" id="expireDate" value="{{ Request::get('expireDate') }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    </select>
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
                    @if (count($visas) > 0)
                    <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    @sortablelink('visaID', 'No.')
                                </th>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('customer.gender', 'Gender')
                                </th>
                                <th>
                                    @sortablelink('is_Active', 'Status')
                                </th>
                                <th>
                                    @sortablelink('customer.nationality', 'Nationality')
                                </th>
                                <th>
                                    @sortablelink('Contact')
                                </th>
                                <th>
                                    @sortablelink('visaType', 'Visa Type')
                                </th>
                                <th>
                                    @sortablelink('expireDate', 'Expiration Date')
                                </th>
                                <th>
                                    @sortablelink('staff_id', 'Incharger')
                                </th>
                                <th>
                                    @sortablelink('userID', 'Inputer')
                                </th>
                                <th class="text-primary">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($visas as $visa)
                            @if ($visa->remindDate <= now())
                                @if ($visa->is_Active == 1)
                                    <tr class="table-danger">
                                @endif
                            @else
                                <tr>
                            @endif
                                    <td>{{ $visa->visaID }}</td>
                                    <td>
                                        <a href="{{ route('visas.visaShow',$visa->visaID) }}">{{ $visa->customer->CustomerName }}</a>
                                    </td>
                                    <td class="text-center">
                                        {{ $visa->customer->gender }}
                                    </td>
                                    <td class="fw-bold">
                                        @if ($visa->remindDate <= now())
                                        <form style="display:inline" method="POST" action="{{ route('visas.reminded', $visa->visaID) }}" onsubmit="return confirm('Are you sure customer {{ $visa->customer->CustomerName }} is reminded ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                Remind Now
                                            </button>
                                        </form>
                                        @else
                                            <span class="badge bg-warning text-white">Remind Date Coming</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $visa->customer->nationality }}
                                    </td>
                                    <td>
                                        @foreach ($customer_contacts as $customer_contact)
                                            @if ($customer_contact->customerID == $visa->customerID)
                                                <a href="{{ route('contacts.edit', $visa->customerID) }}">
                                                    <li>
                                                        {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                                    </li>
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $visa->visaType }}</td>
                                    <td>{{ date('d-M-Y', strtotime($visa->expireDate)) }}</td>
                                    @foreach ($users as $user)
                                        @if ($visa->staff_id == $user->id)
                                            <td>{{ $user->name }}</td>
                                        @endif
                                    @endforeach
                                    <td>{{ $visa->user->name }}</td>
                                    <td class="text-center">
                                        {!! Form::open(['method' => 'get','route' => ['visas.edit', $visa->visaID],'style'=>'display:inline']) !!}
                                            {!! Form::submit('Edit', ['class' => 'btn btn-primary btn-xs']) !!}
                                        {!! Form::close() !!}
                                        <form style="display:inline" method="POST" action="{{ route('visas.delete', $visa->visaID) }}" onsubmit="return confirm('Are you sure? You want to delete customer {{ $visa->customer->CustomerName }} !!!');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                <!--/ Basic Pagination -->
                    @else
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    @sortablelink('visaID', 'No.')
                                </th>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('customer.gender', 'Gender')
                                </th>
                                <th>
                                    @sortablelink('is_Active', 'Status')
                                </th>
                                <th>
                                    @sortablelink('customer.nationality', 'Nationality')
                                </th>
                                <th>
                                    @sortablelink('Contact')
                                </th>
                                <th>
                                    @sortablelink('visaType', 'Visa Type')
                                </th>
                                <th>
                                    @sortablelink('expireDate', 'Expiration Date')
                                </th>
                                <th>
                                    @sortablelink('staff_id', 'Incharger')
                                </th>
                                <th>
                                    @sortablelink('userID', 'Inputer')
                                </th>
                                <th class="text-primary">Actions</th>
                            </tr>
                        </thead>
                    </table><br/>
                    <p class="text-center">No visa customer found.</p>
                @endif
                </div>

                <!-- Basic Pagination -->
                <div class="demo-inline-spacing">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            @if ($visas->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="/visas?page={{ $visas->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                            @endif

                                    @for ($i = 1; $i <= $visas->lastPage(); $i++)
                                        <li class="page-item {{ $visas->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="/visas?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                @if ($visas->currentPage() < $visas->lastPage())
                                    <li class="page-item last">
                                        <a href="/visas?page={{ $visas->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                    </li>
                                @endif
                        </ul>
                    </nav>
                </div>
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