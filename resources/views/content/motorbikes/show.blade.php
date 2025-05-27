@extends('layouts/contentNavbarLayout')

@section('title', 'Motorbike Detail')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('motorbikes.index') }}">Motorbikes Management</a>
        </li>
        <li class="breadcrumb-item active">Motorbike Details</li>
    </ol>
</nav>
{{-- End Breadcrumb --}}
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Motorbike Details</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        {{-- Basic Data --}}
                        <tr>
                            <th class="bg-primary text-white fw-bold">Basic Informations</th>
                            @foreach ($motorbike_details as $motorbike)
                                <th class="fw-bold">Motorbike No.</th>
                                <th class="fw-bold">
                                    <a href="{{ route('motorbikes.edit', $motorbike->motorID) }}">{{ $motorbike->motorno }}</a>
                                </th>
                                <td colspan="2">
                                    @if ($motorbike->motorModel > 0)
                                        <tr>
                                            <td>
                                                <th class="fw-bold">Model</th>
                                                <th class="fw-bold">
                                                    <a href="{{ route('motorbikes.edit', $motorbike->motorID) }}">{{ $motorbike->motorModel }}</a>
                                                </th>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->motorStatus != null)
                                        <tr>
                                            <td>
                                                <th>Status</th>
                                                @if ($motorbike->motorStatus == 1)
                                                    <td>
                                                        <span class="badge bg-primary text-white">
                                                            In Stock
                                                        </span>
                                                    </td>
                                                @elseif ($motorbike->motorStatus == 2)
                                                    <td>
                                                        <span class="badge bg-success text-white">
                                                            On Rent
                                                        </span>
                                                    </td>
                                                @elseif ($motorbike->motorStatus == 3)
                                                    <td>
                                                        <span class="badge bg-danger text-white">
                                                            Sold
                                                        </span>
                                                    </td>
                                                @elseif ($motorbike->motorStatus == 4)
                                                    <td>
                                                        <span class="badge bg-danger text-white">
                                                            Lost / Stolen
                                                        </span>
                                                    </td>
                                                @elseif ($motorbike->motorStatus == 5)
                                                    <td>
                                                        <span class="badge bg-primary text-white">
                                                            Temporary Return
                                                        </span>
                                                    </td>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->year > 0)
                                        <tr>
                                            <td>
                                                <th>Year</th>
                                                <td>{{ $motorbike->year }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->motorType > 0)
                                        <tr>
                                            <td>
                                                <th>Type</th>
                                                <td>{{ $motorbike->motorType }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->motorColor > 0)
                                        <tr>
                                            <td>
                                                <th>Color</th>
                                                <td>{{ $motorbike->motorColor }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->plateNo > 0)
                                        <tr>
                                            <td>
                                                <th>Plate No.</th>
                                                <td>{{ $motorbike->plateNo }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->compensationPrice > 0)
                                        <tr>
                                            <td>
                                                <th>Compensation Price.</th>
                                                <td>${{ $motorbike->compensationPrice }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($motorbike->totalPurchasePrice > 0)
                                        <tr>
                                            <td>
                                                <th>Total Purchase Price.</th>
                                                <td>
                                                    <span class="badge bg-danger">${{ $motorbike->totalPurchasePrice }}</span>
                                                </td>
                                            </td>
                                        </tr>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        {{-- End Basic Data --}}

                        {{-- Rental Data --}}
                        @if (count($rentals) > 0)
                        <tr>
                            <th class="bg-warning text-white">Rental Information</th>
                            <th class="text-primary fw-bold">Rent To</th>
                            @if ($trtc > 1)
                                <th class="text-primary fw-bold">{{ $trtc }} Customers</th>
                            @else
                                <th class="text-primary fw-bold">{{ $trtc }} Customer</th>
                            @endif

                            <td>
                                @if ($frd != null)
                                    <tr>
                                        <td>
                                            <th>Begin Rental</th>
                                            <td>{{ date('d-M-Y', strtotime($frd)) }}</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($lrd != null)
                                    <tr>
                                        <td>
                                            <th>Last Return</th>
                                            <td>{{ date('d-M-Y', strtotime($lrd)) }}</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($tetts != null)
                                    <tr>
                                        <td>
                                            <th>Time of Exchange (To)</th>
                                            <td>{{ $tetts }}</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($tetfs != null)
                                    <tr>
                                        <td>
                                            <th>Time of Exchange (From)</th>
                                            <td>{{ $tetfs }}</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($total_in_stock != null)
                                    <tr>
                                        <td>
                                            <th>Total Purchase Period</th>
                                            <td>{{ $total_in_stock }} Days</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($tids != null)
                                    <tr>
                                        <td>
                                            <th>Total In Stock Period</th>
                                            <td>{{ $tids }} Days</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($tords != null)
                                    <tr>
                                        <td>
                                            <th>Total Rental Period</th>
                                            <td>{{ $tords }} Days</td>
                                        </td>
                                    </tr>
                                @endif
                                @if ($trp != null)
                                    <tr>
                                        <td>
                                            <th>Total Rental Price</th>
                                            <td>
                                                <span class="badge bg-primary">${{ $trp }}</span>
                                            </td>
                                        </td>
                                    </tr>
                                @endif
                            </td>
                        </tr>
                        @if ($tmp > 0)
                            <tr class="table-primary">
                                <th colspan="2" class="text-center fw-bold">
                                    Total Motorbike Profit
                                </th>
                                <th class="fw-bold">
                                    {{ $tmp }}$
                                </th>
                                <td>
                                </td>
                            </tr>
                        @else
                            <tr class="table-danger">
                                <th colspan="2" class="text-danger text-center fw-bold">
                                    Total Motorbike Profit
                                </th>
                                <th class="text-danger fw-bold">
                                    {{ $tmp }}$
                                </th>
                                <td>
                                </td>
                            </tr>
                        @endif
                        @endif
                        {{-- End Rental Data --}}
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@if (count($rentals) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Motorbike Logs</h5>
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
                <label class="col-lg-12 col-form-label">Table Data</label>
            </div>
            <div class="table-responsive text-nowrap">
                @if (count($motorbikes) > 0)
                <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('transactionType', 'Status')
                            </th>
                            <th>
                                @sortablelink('rentalDay', 'Rental Date')
                            </th>
                            <th>
                                @sortablelink('returnDate', 'Return Date')
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
                        @foreach ($motorbikes as $motorbike)
                        @if ($motorbike->price > 0)
                            <tr>
                        @else
                            <tr class="table-danger">
                        @endif
                                <td>
                                    <a href="{{ route('customers.edit', $motorbike->customerID) }}">{{ $motorbike->customer->CustomerName }}</a>
                                </td>
                                @if ($motorbike->transactionType == 'New Rental')
                                    <td>
                                        <span class="badge bg-label-success">{{ $motorbike->transactionType }}</span>
                                    </td>
                                @elseif ($motorbike->transactionType == 'Extension')
                                <td>
                                    <span class="badge bg-label-info">{{ $motorbike->transactionType }}</span>
                                </td>
                                @elseif ($motorbike->transactionType == '5')
                                <td>
                                    <span class="badge bg-label-primary">Temp. Return</span>
                                </td>
                                @elseif ($motorbike->transactionType == 'Return')
                                <td>
                                    <span class="badge bg-label-danger">{{ $motorbike->transactionType }}</span>
                                </td>
                                @else
                                <td>
                                    <span class="badge bg-label-primary">{{ $motorbike->transactionType }}</span>
                                </td>
                                @endif
                                <td>{{ date('d-M-Y', strtotime($motorbike->rentalDay)) }}</td>
                                <td>{{ date('d-M-Y', strtotime($motorbike->returnDate)) }}</td>
                                @if ($motorbike->new_price > 0)
                                    <td>$ {{ $motorbike->new_price }}.00</td>
                                @else
                                    <td class="text-danger">$ {{ $motorbike->new_price }}.00</td>
                                @endif
                                @foreach ($users as $user)
                                    @if ($motorbike->staff_id == $user->id)
                                        <td>{{ $user->name }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $motorbike->user->name }}</td>
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
                                @sortablelink('transactionType', 'Status')
                            </th>
                            <th>
                                @sortablelink('rentalDay', 'Rental Date')
                            </th>
                            <th>
                                @sortablelink('returnDate', 'Return Date')
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
                </table>
                <p class="text-center">No motorbikes found.</p>

            @endif
            </div>
            <!-- Basic Pagination -->
            <div class="demo-inline-spacing">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @if ($motorbikes->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="/motorbikes/{{ $motorbike->motorID }}?page={{ $motorbikes->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                            @endif

                                @for ($i = 1; $i <= $motorbikes->lastPage(); $i++)
                                    <li class="page-item {{ $motorbikes->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="/motorbikes/{{ $motorbike->motorID }}?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($motorbikes->currentPage() < $motorbikes->lastPage())
                                <li class="page-item last">
                                    <a href="/motorbikes/{{ $motorbike->motorID }}?page={{ $motorbikes->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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