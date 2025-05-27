@extends('layouts/contentNavbarLayout')

@section('title', 'Exchange Deposit')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Exchange Deposit</li>
    </ol>
  </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header bg-primary text-white">Exchange Deposit</h5>
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
                                    <select class="form-select" name="preDepositType" id="preDepositType">
                                        <option value="">Pre. Deposit Type</option>
                                        @foreach ($pre_deposit_type as $pdt)
                                            <option value="{{ $pdt->currDepositType }}" @if (Request::get('preDepositType') == $pdt->currDepositType) selected @endif>{{ $pdt->currDepositType }}</option>
                                        @endforeach
                                    </select>
                                    <input name="preDeposit" class="form-control" list="pre_deposit_list" id="preDeposit" value="{{ Request::get('preDeposit') }}" placeholder="Type to search...">
                                    <datalist id="pre_deposit_list">
                                        @foreach ($pre_deposit as $pd)
                                            <option value="{{ $pd->currDeposit }}"> {{ $pd->currDeposit }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <select class="form-select" name="depositType" id="depositType">
                                        <option value="">Deposit Type</option>
                                        @foreach ($deposit_type as $dt)
                                            <option value="{{ $dt->currDepositType }}" @if (Request::get('depositType') == $dt->currDepositType) selected @endif>{{ $dt->currDepositType }}</option>
                                        @endforeach
                                    </select>
                                    <input name="deposit" class="form-control" list="deposit_list" id="deposit" value="{{ Request::get('deposit') }}" placeholder="Type to search...">
                                    <datalist id="deposit_list">
                                        @foreach ($deposit as $d)
                                            <option value="{{ $d->currDeposit }}"> {{ $d->currDeposit }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Exchange Date</span>
                                    <input type="date" name="changeDate" class="form-control" id="changeDate" value="{{ Request::get('changeDate') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    </select>
                                    <button class="btn btn-warning">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- END Filter --}}
                <div class="table-responsive text-nowrap">
                    <div class="ms-3 me-3">
                        {{-- Message --}}
                        @include('layouts.sections.messages')
                        {{-- End Message --}}
                        <label class="col-form-label">Table Data</label>
                    </div>
                    @if (count($dps) > 0)
                    <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    @sortablelink('depositID', 'No.')
                                </th>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('preDepositType', 'Previous Deposit')
                                </th>
                                <th>
                                    @sortablelink('currDepositType', 'Current Deposit')
                                </th>
                                <th>
                                    @sortablelink('created_at', 'Exchange Date')
                                </th>
                                <th>
                                    @sortablelink('staff_id', 'Incharge Staff')
                                </th>
                                <th>
                                    @sortablelink('userID', 'Inputer')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dps as $exchange)
                                    <tr>
                                        <td class="text-center">{{ $exchange->depositID }}</td>
                                        <td>{{ $exchange->customer->CustomerName }}</td>
                                        <td>
                                            @foreach ($pre_deposits as $pre_deposit)
                                                @if ($pre_deposit->rentalID == $exchange->rentalID)
                                                    @if ($pre_deposit->currDepositType == 'Money')
                                                        <li>
                                                            {{ $pre_deposit->currDepositType }} : ${{ $pre_deposit->currDeposit }}
                                                        </li>
                                                    @else
                                                        <li>
                                                            {{ $pre_deposit->currDepositType }} : {{ $pre_deposit->currDeposit }}
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($curr_deposits as $curr_deposit)
                                                @if ($curr_deposit->rentalID == $exchange->rentalID)
                                                    @if ($curr_deposit->currDepositType == 'Money')
                                                        <li>
                                                            {{ $curr_deposit->currDepositType }} : ${{ $curr_deposit->currDeposit }}
                                                        </li>
                                                    @else
                                                        <li>
                                                            {{ $curr_deposit->currDepositType }} : {{ $curr_deposit->currDeposit }}
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ date('d-M-Y', strtotime($exchange->created_at)) }}</td>
                                        @foreach ($users as $user)
                                            @if ($exchange->staff_id == $user->id)
                                                <td>{{ $user->name }}</td>
                                            @endif
                                        @endforeach
                                        @if ($exchange->user->name == 'Admin')
                                            <td>
                                                <span class="badge bg-warning text-white">{{ $exchange->user->name }}</span>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge bg-success text-dark">{{ $exchange->user->name }}</span>
                                            </td>
                                        @endif
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <table class="table table-bordered text-nowrap">
                        <thead>
                          <tr>
                              <th>
                                  @sortablelink('customer.CustomerName', 'Customer Name')
                              </th>
                              <th>
                                  @sortablelink('preMotoID', 'Previous Motor No.')
                              </th>
                              <th>
                                  @sortablelink('currMotorID', 'New Motor No.')
                              </th>
                              <th>
                                  @sortablelink('created_at', 'Exchange Date')
                              </th>
                              <th>
                                  @sortablelink('staff_id', 'Incharge Staff')
                              </th>
                              <th class="text-primary">
                                  Inputer
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
                            @if ($dps->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="/rentals-report/exchange-deposit?page={{ $dps->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                            @endif

                                    @for ($i = 1; $i <= $dps->lastPage(); $i++)
                                        <li class="page-item {{ $dps->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="/rentals-report/exchange-deposit?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                @if ($dps->currentPage() < $dps->lastPage())
                                    <li class="page-item last">
                                        <a href="/rentals-report/exchange-deposit?page={{ $dps->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                    </li>
                                @endif
                        </ul>
                    </nav>
                </div>
                <!--/ Basic Pagination -->
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