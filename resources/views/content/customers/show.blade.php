@extends('layouts/contentNavbarLayout')

@section('title', 'Customer Detail')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('customers.index') }}">Customer Information</a>
        </li>
        <li class="breadcrumb-item active">Customers Details</li>
    </ol>
</nav>
{{-- End Breadcrumb --}}

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Customers Details</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        {{-- Basic Data --}}
                        <tr>
                            <th class="bg-primary text-white fw-bold">Basic Informations</th>
                            <th class="fw-bold">Customer ID</th>
                            @foreach ($customers as $customer)
                                <th class="fw-bold">
                                    <a href="{{ route('customers.edit', $customer->customerID) }}">{{ $customer->customerID }}</a>
                                </th>
                                <td colspan="2">
                                    @if ($customer->CustomerName > 0)
                                        <tr>
                                            <td>
                                                <th class="fw-bold">Customer Name</th>
                                                <th class="fw-bold">
                                                    <a href="{{ route('customers.edit', $customer->customerID) }}">{{ $customer->CustomerName }}</a>
                                                </th>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($customer->gender > 0)
                                        <tr>
                                            <td>
                                                <th>Gender</th>
                                                <td>{{ $customer->gender }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($customer->nationality > 0)
                                        <tr>
                                            <td>
                                                <th>Nationality</th>
                                                <td>{{ $customer->nationality }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <th>Contact</th>
                                            <td>
                                                @foreach ($customer->contact as $contact)
                                                    @if ($contact->is_Active == 1)
                                                        <li>
                                                            {{ $contact->contactType }} : {{ $contact->contactDetail }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </td>
                                    </tr>
                                    
                                    @if ($customer->address > 0)
                                        <tr>
                                            <td>
                                                <th>Address</th>
                                                <td>{{ $customer->address }}</td>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($customer->comment > 0)
                                        <tr>
                                            <td>
                                                <th>Comment</th>
                                                <td>{{ $customer->comment }}</td>
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
                            <th class="bg-warning text-white fw-bold">
                                Motorbike Rental
                                <button id="btnRental"class="btn btn-xs bg-white text-warning" style="float: right">
                                    <i id="arrowRental" class="fa fa-arrow-circle-down fa-lg" aria-hidden="true"></i>
                                </button>
                            </th>
                            <th class="fw-bold">Latest Status: Incharge Staff <span class="text-danger">({{ $staff_name }})</span></th>
                            @foreach ($rentals as $rental)
                            <td class="fw-bold">
                                @foreach ($rental_status as $status)
                                    @if ($status->transactionType == 'New Rental')
                                            <span class="badge bg-success text-white">{{ $rental->transactionType }}</span>
                                    @elseif ($status->transactionType == 'Extension')
                                        <span class="badge bg-info text-white">{{ $rental->transactionType }}</span>
                                    @elseif ($status->transactionType == 'Exchange')
                                        <span class="badge bg-secondary text-white">{{ $rental->transactionType }}</span>
                                    @elseif ($status->transactionType == '3')
                                        <span class="badge bg-danger text-white">Sold</span>
                                    @elseif ($status->transactionType == '4')
                                        <span class="badge bg-danger text-white">Stolen</span>
                                    @elseif ($status->transactionType == '5')
                                        <span class="badge bg-primary text-white">Temp. Return</span>
                                    @elseif ($status->transactionType == 'Return')
                                        <span class="badge bg-danger text-white">Return</span>
                                    @else
                                        <span class="badge bg-primary text-white">No Data</span>
                                    @endif
                                @endforeach
                            </td>
                                <td>
                                    <tr id="deposit" style="display: none">
                                        <td>
                                            <th>Latest Deposit</th>
                                            <td>
                                                @foreach ($customer->deposit as $deposit)
                                                    @if ($deposit->is_Active == 1)
                                                        <li>
                                                            {{ $deposit->currDepositType }} : {{ $deposit->currDeposit }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </td>
                                    </tr>
                                    @if ($rental->created_at != null)
                                        <tr id="br" style="display: none">
                                            <td>
                                                <th>Begin Rental</th>
                                                @foreach ($begin_rentals as $begin_rental)
                                                    <td>{{ $begin_rental->created_at->format('d-M-Y') }}</td>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                    <tr id="lastRental" style="display: none">
                                        <td>
                                            <th>Last Rental</th>
                                            @foreach ($rental_status as $last_rental)
                                            <td>{{ $last_rental->updated_at->format('d-M-Y') }}</td>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr id="timeOE" style="display: none">
                                        <td>
                                            <th>Time of Extension</th>
                                            <td>{{ $toE }}</td>
                                        </td>
                                    </tr>
                                    <tr id="totalRP" style="display: none">
                                        <td>
                                            <th>Total Rental Period</th>
                                            <td>{{ $trP }} Days</td>
                                        </td>
                                    </tr>
                                </td>
                                @if ($trPrice > 0)
                                <tr id="tp" style="display: none">
                                    <td class="justify-content-center">
                                        <th>Total Price</th>
                                        <td>${{ $trPrice }}</td>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tr>
                        @endif
                        {{-- End Rental Data --}}

                        {{-- Visa Data --}}
                        @if (count($visas) > 0)
                        <tr>
                            <th class="bg-dark text-white fw-bold">
                                Visa Extension
                                <button id="btnVisa"class="btn btn-xs bg-white text-dark" style="float: right">
                                    <i id="arrowVisa" class="fa fa-arrow-circle-down fa-lg" aria-hidden="true"></i>
                                </button>
                            </th>
                            @foreach ($visas as $visa)
                            <th class="fw-bold">Visa Reiminder Status</th>
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
                                <tr id="jv_visa_type" style="display: none">
                                    <td>
                                        <th>Latest Visa Type</th>
                                        <td>{{ $visa->visaType }}</td>
                                    </td>
                                </tr>
                                @if ($visa->amount > 0)
                                    <tr id="jv_visa_amount" style="display: none">
                                        <td>
                                            <th>Latest Passport Amount</th>
                                                @if ($visa->amount == 1)
                                                    <td>{{ $visa->amount }} Passport</td>
                                                @else
                                                    <td>{{ $visa->amount }} Passports</td>
                                                @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($v_last_expirations != null)
                                <tr id="jv_visa_exDate" style="display: none">
                                    <td>
                                        <th>Last Expiration Date</th>
                                            <td>{{ $v_last_expirations->format('d-M-Y') }}</td>
                                    </td>
                                </tr>
                                @endif
                                @if ($v_last_extensions != null)
                                <tr id="jv_visa_lastExt" style="display: none">
                                    <td>
                                        <th>Last Extension</th>
                                            <td>{{ $v_last_extensions->format('d-M-Y') }}</td>
                                    </td>
                                </tr>
                                @endif
                                <tr id="jv_visa_ToE" style="display: none">
                                    <td>
                                        <th>Time of Extension</th>
                                        <td>{{ $vToE }}</td>
                                    </td>
                                </tr>
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        {{-- End Visa Data --}}

                        {{-- WP Data --}}
                        @if (count($wps) > 0)
                        <tr>
                            <th class="bg-secondary text-white fw-bold">
                                Work Permit Renewal
                                <button id="btnWP"class="btn btn-xs bg-white text-secondary" style="float: right">
                                    <i id="arrowWP" class="fa fa-arrow-circle-down fa-lg" aria-hidden="true"></i>
                                </button>
                            </th>
                            @foreach ($wps as $wp_s)
                            <th class="fw-bold">WP Reiminder Status</th>
                                @if ($wp_s->is_Active == 1)
                                    <td class="fw-bold">
                                        <span class="badge bg-warning text-white">Remind Date Coming</span>
                                    </td>
                                @else
                                    <td class="fw-bold">
                                        <span class="badge bg-danger text-white">Reminded</span>
                                    </td>
                                @endif
                            <td>
                                @if ($w_last_expirations != null)
                                <tr id="jv_wp_lastExpDate" style="display: none">
                                    <td>
                                            <th>Last Expiration Date</th>
                                            <td>{{ $w_last_expirations->format('d-M-Y') }}</td>
                                    </td>
                                </tr>
                                @endif
                                @if ($w_last_extensions != null)
                                <tr id="jv_wp_LastRDate" style="display: none">
                                    <td>
                                            <th>Last Renawal Date</th>
                                            <td>{{ $w_last_extensions->format('d-M-Y') }}</td>
                                    </td>
                                </tr>
                                @endif
                                <tr id="jv_wp_TOE" style="display: none">
                                    <td>
                                        <th>Time of Extension</th>
                                        <td>{{ $wToE }}</td>
                                    </td>
                                </tr>
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        {{-- End WP Data --}}
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- RENTAL LOG --}}
<div class="row mb-3" id="rentalDiv" style="display: none">
    <div class="col-md-12">
        <div class="card">
                <h6 class="card-header bg-warning text-white fw-bold">RENTAL LOGS</h6>
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
                                        <option value="{{ $motorbike->motorno }}">{{ $motorbike->motorno }}</option>
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

{{-- VISA LOG --}}
@if (count($visas) > 0)
<div class="row mb-3" id="visaDiv" style="display: none">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-dark text-white fw-bold">VISA LOGS</h5>
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
{{-- END VISA LOG --}}

{{-- WP LOG --}}
@if (count($wps) > 0)
<div class="row mb-3" id="wpDiv" style="display: none">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-secondary text-white fw-bold">WORK PERMIT LOGS</h5>
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
{{-- END WP LOG --}}

<script>
    const btnVisa = document.getElementById("btnVisa");

    const jv_visa_type = document.getElementById("jv_visa_type");
    const jv_visa_amount = document.getElementById("jv_visa_amount");
    const jv_visa_exDate = document.getElementById("jv_visa_exDate");
    const jv_visa_lastExt = document.getElementById("jv_visa_lastExt");
    const jv_visa_ToE = document.getElementById("jv_visa_ToE");

    const visaDivArr = [jv_visa_type, jv_visa_amount, jv_visa_exDate, jv_visa_lastExt, jv_visa_ToE];
    const visaDiv = document.getElementById("visaDiv");

btnVisa.addEventListener("click", function() {
    visaDivArr.forEach(function(div) {
    if (div.style.display === "none") {
        div.style.display = "table-row";
        visaDiv.style.display = "block";
    } else {
        div.style.display = "none";
        visaDiv.style.display = "none";
    }
  });
});
    const arrowVisa = document.getElementById("arrowVisa");

    btnVisa.addEventListener("click", function() {
      // Check if icon has "fa-rotate-270" class
      if (arrowVisa.classList.contains("fa-rotate-180")) {
        // Remove "fa-rotate-270" class to reset rotation
        arrowVisa.classList.remove("fa-rotate-180");
      } else {
        // Add "fa-rotate-270" class to rotate 270 degrees
        arrowVisa.classList.add("fa-rotate-180");
      }
    });
</script>

<script>
    const btnWP = document.getElementById("btnWP");

    const jv_wp_lastExpDate = document.getElementById("jv_wp_lastExpDate");
    const jv_wp_LastRDate = document.getElementById("jv_wp_LastRDate");
    const jv_wp_TOE = document.getElementById("jv_wp_TOE");

    const wpDivArr = [jv_wp_lastExpDate, jv_wp_LastRDate, jv_wp_TOE];
    
    const wpDiv = document.getElementById("wpDiv");

btnWP.addEventListener("click", function() {
    wpDivArr.forEach(function(div) {
    if (div.style.display === "none") {
        div.style.display = "table-row";
        wpDiv.style.display = "block";
    } else {
        div.style.display = "none";
        wpDiv.style.display = "none";
    }
  });
});
    const arrowWP = document.getElementById("arrowWP");
    
    btnWP.addEventListener("click", function() {
      // Check if icon has "fa-rotate-270" class
      if (arrowWP.classList.contains("fa-rotate-180")) {
        // Remove "fa-rotate-270" class to reset rotation
        arrowWP.classList.remove("fa-rotate-180");
      } else {
        // Add "fa-rotate-270" class to rotate 270 degrees
        arrowWP.classList.add("fa-rotate-180");
      }
    });
</script>

<script>
    const rentalBTN = document.getElementById("btnRental");

    const deposit = document.getElementById("deposit");
    const br = document.getElementById("br");
    const lastRental = document.getElementById("lastRental");
    const timeOE = document.getElementById("timeOE");
    const totalRP = document.getElementById("totalRP");
    const tp = document.getElementById("tp");

    const rentalDivArr = [deposit, br, lastRental, timeOE, totalRP, tp];
    
    const rentalDiv = document.getElementById("rentalDiv");

btnRental.addEventListener("click", function() {
    rentalDivArr.forEach(function(div) {
    if (div.style.display === "none") {
        div.style.display = "table-row";
        rentalDiv.style.display = "block";
    } else {
        div.style.display = "none";
        rentalDiv.style.display = "none";
    }
  });
});

    const arrowRental = document.getElementById("arrowRental");
    
    rentalBTN.addEventListener("click", function() {
      // Check if icon has "fa-rotate-270" class
      if (arrowRental.classList.contains("fa-rotate-180")) {
        // Remove "fa-rotate-270" class to reset rotation
        arrowRental.classList.remove("fa-rotate-180");
      } else {
        // Add "fa-rotate-270" class to rotate 270 degrees
        arrowRental.classList.add("fa-rotate-180");
      }
    });
</script>

<style>
    .fa-arrow-circle-down {
      transition: transform 0.2s ease;
    }
  </style>
@endsection