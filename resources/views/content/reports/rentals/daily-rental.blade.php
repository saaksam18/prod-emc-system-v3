@extends('layouts/contentNavbarLayout')

@section('title', 'Daily Rental')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        <li class="breadcrumb-item active">Daily Rental Transacton</li>
    </ol>
</nav>

<div class="card mb-3">
    <h5 class="card-header bg-primary text-white">Daily Rental Transaction Detail</h5>
    <div class="row">
        <div class="col-lg-4">
            <div class="ms-3 me-3">
                <label class="col-form-label">Filter</label>
                <form action="" method="get">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">Date</span>
                        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                        <input class="form-control" type="date" name="created_at" value="{{ Request::get('created_at') }}" id="created_at">
                        <button type="submit" class="btn btn-warning">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <td colspan="2" class="bg-primary text-white">
                                    Rental Transaction
                                </td>
                            </tr>
                            <tr>
                                <td class="text-primary">New Rental</td>
                                <td>
                                    <span class="badge bg-primary text-white">{{ $newRentals }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-primary">Extension</th>
                                <td>
                                    <span class="badge bg-info text-white">{{ $extensions }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-primary">Exchange Motorbike</th>
                                <td>
                                    <span class="badge bg-secondary text-white">{{ $exchangeMotors }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-primary">Exchange Deposit</th>
                                <td>
                                    <span class="badge bg-secondary text-white">{{ $exchangeDeposits }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-primary">Return</th>
                                <td>
                                    <span class="badge bg-danger text-white">{{ $returns }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="table-footer fw-bold bg-dark text-white text-end">Total : {{ $totals }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <td colspan="5" class="bg-primary text-white">
                                    Rental Percentage
                                </td>
                            </tr>
                            <tr>
                                <td class="text-primary text-center">Motorbike Type</td>
                                <td class="text-primary text-center">Total</td>
                                <td class="text-primary text-center">In Stock</td>
                                <td class="text-primary text-center">On Rent</td>
                                <td class="text-primary text-center">% of Rental</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Big Auto</td>
                                <td class="text-center">{{ $bigATs }}</td>
                                <td class="text-center">{{ $bigATis }}</td>
                                <td class="text-center">{{ $bigATor }}</td>
                                <td class="text-center">{{ $totalBigATPercentageFormatted }}%</td>
                            </tr>
                            <tr>
                                <td>Auto</td>
                                <td class="text-center">{{ $ats }}</td>
                                <td class="text-center">{{ $atis }}</td>
                                <td class="text-center">{{ $ator }}</td>
                                <td class="text-center">{{ $totalATPercentageFormatted }}%</td>
                            </tr>
                            <tr>
                                <td>50cc Auto</td>
                                <td class="text-center">{{ $ccATs }}</td>
                                <td class="text-center">{{ $ccATis }}</td>
                                <td class="text-center">{{ $ccATor }}</td>
                                <td class="text-center">{{ $total50ccATPercentageFormatted }}%</td>
                            </tr>
                            <tr>
                                <td>Manual</td>
                                <td class="text-center">{{ $mts }}</td>
                                <td class="text-center">{{ $mtis }}</td>
                                <td class="text-center">{{ $mtor }}</td>
                                <td class="text-center">{{ $totalMTPercentageFormatted }}%</td>
                            </tr>
                            <tr class="bg-dark">
                                <th class="text-white text-center">Total</th>
                                <th class="text-white text-center">{{ $totalMotors }}</th>
                                <th class="text-white text-center">{{ $totalInstock }}</th>
                                <th class="text-white text-center">{{ $totalOnRent }}</th>
                                <th class="text-white text-center">{{ $totalPercentageFormatted }}%</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
</div>
            <div class="ms-3 me-3">
                <label class="col-form-label">Data Table</label>
            </div>
            <div class="table-responsive text-nowrap">
                @if (count($rentals) > 0)
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('motorID', 'Motor No.')
                            </th>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('Contact')
                            </th>
                            <th>
                                @sortablelink('Deposit')
                            </th>
                            <th>
                                @sortablelink('created_at', 'Payment Date')
                            </th>
                            <th>
                                @sortablelink('transactionType', 'Status')
                            </th>
                            <th>
                                @sortablelink('price', 'Price')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rentals as $rental)
                            <tr>
                                <td class="text-center">{{ $rental->motorInfor->motorno }}</td>
                                <td>{{ $rental->customer->CustomerName }}</td>
                                <td>
                                    @foreach ($customer_contacts as $customer_contact)
                                        @if ($customer_contact->customerID == $rental->customerID)
                                            <li>
                                                {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                            </li>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($rental_deposits as $rental_deposit)
                                        @if ($rental_deposit->rentalID == $rental->rentalID)
                                            @if ($rental_deposit->currDepositType == 'Money')
                                                <li>
                                                    {{ $rental_deposit->currDepositType }} : ${{ $rental_deposit->currDeposit }}
                                                </li>
                                            @else
                                                <li>
                                                    {{ $rental_deposit->currDepositType }} : {{ $rental_deposit->currDeposit }}
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ date('d-M-Y', strtotime($rental->created_at)) }}</td>
                                @if ($rental->transactionType == 'New Rental')
                                    <td>
                                        <span class="badge bg-label-success">{{ $rental->transactionType }}</span>
                                    </td>
                                @elseif ($rental->transactionType == 'Extension')
                                <td>
                                    <span class="badge bg-label-info">{{ $rental->transactionType }}</span>
                                </td>
                                @elseif ($rental->transactionType == 'Return')
                                <td>
                                    <span class="badge bg-label-danger">Return</span>
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
                                <td>{{ $rental->price }}$</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('motorID', 'Motor No.')
                            </th>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('Contact')
                            </th>
                            <th>
                                @sortablelink('Deposit')
                            </th>
                            <th>
                                @sortablelink('created_at', 'Payment Date')
                            </th>
                            <th>
                                @sortablelink('transactionType', 'Status')
                            </th>
                            <th>
                                @sortablelink('price', 'Price')
                            </th>
                        </tr>
                    </thead>
              </table><br/>
              <p class="text-center">No transactions found.</p>
            @endif
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