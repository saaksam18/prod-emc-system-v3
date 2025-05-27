@extends('layouts/contentNavbarLayout')

@section('title', 'Rental Details')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('rentals.index') }}">Rental Management</a>
      </li>
      <li class="breadcrumb-item active">Rental Details</li>
    </ol>
  </nav>
    
    {{-- RENTAL DETAIL --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header bg-primary text-white">Rental Details</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="bg-warning text-white fw-bold">Motorbike Informations</th>
                                @foreach ($rentals as $rental)
                                <th class="fw-bold">Motorbike No.</th>
                                    <th class="fw-bold">
                                        <a href="{{ route('motorbikes.edit',$rental->motorID) }}">{{ $rental->motorInfor->motorno }}</a>
                                    </th>
                                    <td colspan="2">
                                        @if ($rental->transactionType > 0)
                                            <tr>
                                                <td>
                                                    <th>Status: Incharge Staff <span class="text-danger">({{ $staff_name }})</span></th>
                                                    @if ($rental->transactionType == 'New Rental')
                                                        <td>
                                                            <span class="badge bg-label-success">{{ $rental->transactionType }}</span>
                                                        </td>
                                                    @elseif ($rental->transactionType == 'Extension')
                                                    <td>
                                                        <span class="badge bg-label-info">{{ $rental->transactionType }}</span>
                                                    </td>
                                                    @elseif ($rental->transactionType == 'Exchange')
                                                    <td>
                                                        <span class="badge bg-label-primary">{{ $rental->transactionType }}</span>
                                                    </td>
                                                    @elseif ($rental->transactionType == '3')
                                                    <td>
                                                        <span class="badge bg-label-danger">Sold</span>
                                                    </td>
                                                    @elseif ($rental->transactionType == '4')
                                                    <td>
                                                        <span class="badge bg-label-danger">Stolen</span>
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
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($rental->transactionType === 'New Rental')
                                            <tr>
                                                <td>
                                                    <th>Rental Date</th>
                                                    <td>{{ date('d-M-Y', strtotime($rental->rentalDay)) }}</td>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <th>Pre. Return Date</th>
                                                    <td>{{ date('d-M-Y', strtotime($rental->rentalDay)) }}</td>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($rental->returnDate > 0)
                                            <tr>
                                                <td>
                                                    <th>Return Date</th>
                                                    <td>{{ date('d-M-Y', strtotime($rental->returnDate)) }}</td>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($rental->commingDate > 0)
                                            <tr>
                                                <td>
                                                    <th>Coming Date</th>
                                                    <td>{{ date('d-M-Y', strtotime($rental->commingDate)) }}</td>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($rental->rentalPeriod > 0)
                                            <tr>
                                                <td>
                                                    <th>Rental Period</th>
                                                    <td>{{ $rental->rentalPeriod }} Days</td>
                                                </td>
                                            </tr>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="bg-primary text-white fw-bold">Customer Informations</th>
                                @foreach ($rentals as $rental)
                                <th class="fw-bold">Name</th>
                                <th class="fw-bold">
                                    <a href="{{ route('customers.edit',$rental->customerID) }}">{{ $rental->customer->CustomerName }}</a>
                                </th>
                                    <td colspan="2">
                                        @if ($rental->customer->nationality > 0)
                                        <tr>
                                            <td>
                                                <th>Nationality</th>
                                                <td>{{ $rental->customer->nationality }}</td>
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($rental->customer->gender > 0)
                                        <tr>
                                            <td>
                                                <th>Gender</th>
                                                <td>{{ $rental->customer->gender }}</td>
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($rental->contact != null)
                                            <tr>
                                                <td>
                                                    <th>Contact</th>
                                                    <td>
                                                        @foreach ($contacts as $contact)
                                                            @if ($contact->is_Active == 1)
                                                                <li>
                                                                    {{ $contact->contactType }} : {{ $contact->contactDetail }}
                                                                </li>
                                                            @else
                                                                <li>
                                                                    No Data
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($rental->deposit != null)
                                            <tr>
                                                <td>
                                                    <th>Deposit</th>
                                                    <td>
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
                                                    </td>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($rental->customer->address > 0)
                                        <tr>
                                            <td>
                                                <th>Address</th>
                                                <td>{{ $rental->customer->address }}</td>
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($rental->customer->comment > 0)
                                        <tr>
                                            <td>
                                                <th>Remark</th>
                                                <td>{{ $rental->customer->comment }}</td>
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($rental->price > 0)
                                        <tr>
                                            <td class="justify-content-center">
                                                <th>Price</th>
                                                <td><span class="badge bg-danger text-white ps-3 pe-3 pt-2 pb-2">${{ $rental->price }}</span></td>
                                            </td>
                                        </tr>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- END RENTAL DETAIL --}}

    {{-- RENTAL LOG --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                    <h6 class="card-header bg-primary text-white">RENTAL LOGS</h6>
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                          <label class="col-lg-12 col-form-label">Filter</label>
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
                                    <span class="input-group-text">Price</span>
                                    <input name="price" class="form-control" list="price_list" id="price" value="{{ Request::get('price') }}" placeholder="Type to search...">
                                    <datalist id="price_list">
                                        @foreach ($rental_price_drop as $motorbike)
                                            <option value="{{ $motorbike->price }}"> {{ $motorbike->price }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <button class="btn btn-warning">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive text-nowrap">
                    @if (count($rental_logs) > 0)
                    <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    @sortablelink('motorID', 'Motor No.')
                                </th>
                                <th>
                                    @sortablelink('Deposit')
                                </th>
                                <th>
                                    @sortablelink('Contact')
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
                                    @sortablelink('userID', 'Inputer')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rental_logs as $rental)
                                <tr>
                                    <td class="text-center">
                                        {{ $rental->motorInfor->motorno }} 
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @foreach ($contacts as $contact)
                                        <li>
                                            {{ $contact->contactType }} : {{ $contact->contactDetail }}
                                        </li>
                                        @endforeach
                                    </td>
                                    
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
                                    @elseif ($rental->transactionType == 'Return')
                                    <td>
                                        <span class="badge bg-label-danger">{{ $rental->transactionType }}</span>
                                    </td>
                                    @else
                                    <td>
                                        <span class="badge bg-label-primary">{{ $rental->transactionType }}</span>
                                    </td>
                                    @endif
                                    <td>{{ date('d-M-Y', strtotime($rental->rentalDay)) }}</td>
                                    <td>{{ date('d-M-Y', strtotime($rental->returnDate)) }}</td>
                                    <td>${{ $rental->price }}</td>
                                    <td>{{ $rental->user->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    @sortablelink('motorInfor.motorno', 'Motorbike No.')
                                </th>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('transactionType', 'Transaction Type')
                                </th>
                                <th>
                                    @sortablelink('returnDate', 'Return Date')
                                </th>
                                <th>
                                    @sortablelink('price', 'Price')
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
                            @if ($rental_logs->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="/rentals/{{ $rental->rentalID }}?page={{ $rental_logs->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                            @endif

                                    @for ($i = 1; $i <= $rental_logs->lastPage(); $i++)
                                        <li class="page-item {{ $rental_logs->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="/rentals/{{ $rental->rentalID }}?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                @if ($rental_logs->currentPage() < $rental_logs->lastPage())
                                    <li class="page-item last">
                                        <a href="/rentals/{{ $rental->rentalID }}?page={{ $rental_logs->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                    </li>
                                @endif
                        </ul>
                    </nav>
                </div>
                <!--/ Basic Pagination -->
            </div>
         </div>
    </div>
    {{-- END RENTAL LOG --}}

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