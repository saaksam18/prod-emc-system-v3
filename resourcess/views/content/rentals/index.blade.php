@extends('layouts/contentNavbarLayout')

@section('title', 'Rental Management')

@section('content')

<nav aria-label="breadcrumb">
<ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
    <li class="breadcrumb-item active">Rental Management</li>
</ol>
</nav>
<div class="row mb-3">
    <div class="col-sm-10">
        <button type="button" class="btn btn-warning">
        <a href="{{ route('rentals.create') }}" style="color: white">Create New Transaction</a>
        </button>
    </div>
</div>

    {{-- Header --}}
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="p-2 text-center">
                    <div class="card-title">
                        <h5 class="text-nowrap mt-1 text-start ms-2">Motorbikes</h5>
                        <div class="row">
                            <div class="col-4">
                                <span class="badge bg-label-warning rounded-pill mt-3">In Stock</span>
                                <h6 class="mb-0 mt-1">{{ $totalInstock }} Scooters</h6>
                            </div>
                            <div class="col-4">
                                <span class="badge bg-label-success rounded-pill mt-3">On Rent</span>
                                <h6 class="mb-0 mt-1">{{ $totalOnRent }} Scooters</h6>
                            </div>
                            <div class="col-4">
                                <span class="badge bg-label-primary rounded-pill mt-3">Total</span>
                                <h6 class="mb-0 mt-1">{{ $totalMotors }} Scooters</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 d-flex justify-content-between gap-4">
            <div class="col-lg-3 mb-3">
                <div class="card">
                    <div class="p-2">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="Credit Card" class="rounded">
                            </div>
                        </div>
                    <span class="fw-semibold d-block mb-1">Cash Deposit</span>
                    <h3 class="card-title text-nowrap mb-1">${{ $countCashs }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-3">
                <div class="card">
                    <div class="p-2">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/cc-primary.png')}}" alt="Credit Card" class="rounded">
                            </div>
                        </div>
                    <span class="fw-semibold d-block mb-1">P.P Deposit</span>
                    <h3 class="card-title mb-1">{{ $countPPs }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-3">
                <div class="card">
                    <div class="p-2">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/chart.png')}}" alt="Credit Card" class="rounded">
                            </div>
                        </div>
                    <span class="fw-semibold d-block mb-1">Customer Late Payment</span>
                    <h3 class="card-title mb-1">{{ $cus_late_payment }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Header --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <h5 class="card-header bg-primary text-white">Rental Management</h5>
                {{-- Filter --}}
                <form action="" method="GET">
                    <div class="ms-3 me-3">
                        <div class="row">
                            <label class="col-form-label">Filter</label>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Customer Name</span>
                                    <input name="CustomerName" class="form-control" list="customers" id="CustomerName" value="{{ Request::get('CustomerName') }}" placeholder="Type to search...">
                                    <datalist id="customers">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->CustomerName }}"> {{ $customer->CustomerName }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Motorbike No.</span>
                                    <input name="motorno" class="form-control" list="motorno_list" id="motorno" value="{{ Request::get('motorno') }}" placeholder="Type to search...">
                                    <datalist id="motorno_list">
                                        @foreach ($motorbike_no_drop as $motorbike)
                                            <option value="{{ $motorbike->motorno }}"> {{ $motorbike->motorno }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <select class="form-select" name="motorType" id="motorType">
                                        <option value="">Type</option>
                                        <option value="1" @if (Request::get('motorType') == 1) selected @endif>Big AT</option>
                                        <option value="2" @if (Request::get('motorType') == 2) selected @endif>Auto</option>
                                        <option value="3" @if (Request::get('motorType') == 3) selected @endif>50cc AT</option>
                                        <option value="4" @if (Request::get('motorType') == 4) selected @endif>Manual</option>
                                    </select>
                                    <input name="motorModel" class="form-control" list="motorModel_list" id="motorModel" value="{{ Request::get('motorModel') }}" placeholder="Type to search...">
                                    <datalist id="motorModel_list">
                                        @foreach ($rentals as $rental)
                                            <option value="{{ $rental->motorInfor->motorModel }}"> {{ $rental->motorInfor->motorModel }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Status</span>
                                    <select class="form-select" name="transactionType" id="transactionType">
                                        <option value="">-- Status --</option>
                                        @foreach ($rental_tran_drop as $motorbike)
                                            @if ($motorbike->transactionType == 5)
                                                <option value="5" @if (Request::get('transactionType') == 5) selected @endif>Temp. Return</option>
                                            @elseif ($motorbike->transactionType == 3)
                                                <option value="3" @if (Request::get('transactionType') == 3) selected @endif>Sold</option>
                                            @elseif ($motorbike->transactionType == 4)
                                                <option value="4" @if (Request::get('transactionType') == 4) selected @endif>Stolen</option>
                                            @else
                                                <option value="{{ $motorbike->transactionType }}" @if (Request::get('transactionType') == $motorbike->transactionType) selected @endif>{{ $motorbike->transactionType }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                    <span class="input-group-text">Rental Date</span>
                                    <input class="form-control" type="date" name="rentalDay" value="{{ Request::get('rentalDay') }}" id="rentalDay">
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Return Date</span>
                                    <input class="form-control" type="date" name="returnDate" value="{{ Request::get('returnDate') }}" id="returnDate">
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Price</span>
                                    <input name="price" class="form-control" list="price_list" id="price" value="{{ Request::get('price') }}" placeholder="Type to search...">
                                    <datalist id="price_list">
                                        @foreach ($rental_price_drop as $motorbike)
                                            <option value="{{ $motorbike->price }}"> {{ $motorbike->price }} </option>
                                        @endforeach
                                    </datalist>
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
                {{-- End Filter --}}
            <div class="ms-3 me-3">
                {{-- Message --}}
                @include('layouts.sections.messages')
                {{-- End Message --}}
                <label class="col-form-label">Table Data</label>
            </div>
                <div class="table-responsive text-nowrap">
                    @if (count($rentals) > 0)
                    <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-primary">Actions</th>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('motorInfor.motorno', 'Motor No.')
                                </th>
                                <th>
                                    @sortablelink('motorInfor.motorType', 'Type')
                                </th>
                                <th>
                                    @sortablelink('motorInfor.motorModel', 'Model')
                                </th>
                                <th>
                                    @sortablelink('returnDate', 'Return Date')
                                </th>
                                <th>
                                    @sortablelink('transactionType', 'Status')
                                </th>
                                <th class="text-primary">
                                    Deposit
                                </th>
                                <th>
                                    @sortablelink('price', 'Price')
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
                            @foreach ($rentals as $rental)
                            @if ($rental->returnDate <= now())
                                <tr class="table-danger">
                            @else
                                <tr>
                            @endif
                                    <td>
                                        {!! Form::open(['method' => 'get','route' => ['rentals.edit', $rental->rentalID],'style'=>'display:inline']) !!}
                                            {!! Form::submit('Actions', ['class' => 'btn btn-primary btn-xs']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('rentals.show',$rental->rentalID) }}">{{ $rental->customer->CustomerName }}</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('rentals.changeMotorEdit',$rental->rentalID) }}">{{ $rental->motorInfor->motorno }}</a>
                                    </td>
                                    @if ($rental->motorInfor->motorType == 1)
                                        <td class="text-center">Big AT</td>
                                    @elseif ($rental->motorInfor->motorType == 2)
                                        <td class="text-center">Auto</td>
                                    @elseif ($rental->motorInfor->motorType == 3)
                                        <td class="text-center">50cc AT</td>
                                        @elseif ($rental->motorInfor->motorType == 4)
                                        <td class="text-center">Manual</td>
                                    @endif
                                    <td>{{ $rental->motorInfor->motorModel }}</td>
                                    <td>{{ date('d-M-Y', strtotime($rental->returnDate)) }}</td>
                                    @if ($rental->transactionType == 'New Rental')
                                        <td>
                                            <span class="badge bg-label-success">{{ $rental->transactionType }}</span>
                                        </td>
                                    @elseif ($rental->transactionType == 'Extension')
                                    <td>
                                        <span class="badge bg-label-info">{{ $rental->transactionType }}</span>
                                    </td>
                                    @elseif ($rental->transactionType == '5')
                                    <td>
                                        <span class="badge bg-label-primary">Temp. Return</span>
                                    </td>
                                    @else
                                    <td>
                                        <span class="badge bg-label-primary">{{ $rental->transactionType }}</span>
                                    </td>
                                    @endif
                                    <td>
                                        <a href="{{ route('rentals.exchange-deposit',$rental->rentalID) }}">
                                            @foreach ($rental_deposits as $rental_deposit) 
                                                @if ($rental_deposit->rentalID == $rental->rentalID)
                                                    @if ($rental_deposit->customerID == $rental->customerID)
                                                        @if ($rental_deposit->currDepositType == 'Money')
                                                            <li>
                                                                {{ $rental_deposit->currDepositType }} : ${{ $rental_deposit->currDeposit }}
                                                            </li>
                                                        @else
                                                            <li>
                                                                {{ $rental_deposit->currDepositType }} : {{ $rental_deposit->currDeposit }}
                                                            </li>
                                                        @endif
                                                    @else
                                                        No Data
                                                    @endif
                                                @endif
                                            @endforeach
                                        </a>
                                    </td>
                                    <td>${{ $rental->price }}</td>
                                    @foreach ($users as $user)
                                        @if ($rental->staff_id == $user->id)
                                            <td>{{ $user->name }}</td>
                                        @endif
                                    @endforeach
                                    <td>{{ $rental->user->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        <!--/ Basic Pagination -->
                    @else
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-primary">Actions</th>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('motorInfor.motorno', 'Motor No.')
                                </th>
                                <th>
                                    @sortablelink('motorInfor.motorType', 'Type')
                                </th>
                                <th>
                                    @sortablelink('motorInfor.motorModel', 'Model')
                                </th>
                                <th>
                                    @sortablelink('returnDate', 'Return Date')
                                </th>
                                <th>
                                    @sortablelink('transactionType', 'Status')
                                </th>
                                <th class="text-primary">
                                    Deposit
                                </th>
                                <th>
                                    @sortablelink('price', 'Price')
                                </th>
                                <th>
                                    @sortablelink('staff_id', 'Incharger')
                                </th>
                                <th>
                                    @sortablelink('userID', 'Inputer')
                                </th>
                            </tr>
                        </thead>
                  </table><br/>
                  <p class="text-center">No transactions found.</p>
                @endif
                </div>

                <!-- Basic Pagination -->
                <div class="demo-inline-spacing">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            @if ($rentals->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="/rentals?page={{ $rentals->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                            @endif

                                    @for ($i = 1; $i <= $rentals->lastPage(); $i++)
                                        <li class="page-item {{ $rentals->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="/rentals?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                @if ($rentals->currentPage() < $rentals->lastPage())
                                    <li class="page-item last">
                                        <a href="/rentals?page={{ $rentals->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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