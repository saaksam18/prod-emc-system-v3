@extends('layouts/contentNavbarLayout')

@section('title', 'Exchange Motorbike')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Exchange Motorbike</li>
    </ol>
  </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header bg-primary text-white">Exchange Motorbikes</h5>
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
                                    <span class="input-group-text">Pre. Motorbike</span>
                                    <input name="preMotorID" class="form-control" list="preMotorID_list" id="preMotorID" value="{{ Request::get('preMotorID') }}" placeholder="Type to search...">
                                    <datalist id="preMotorID_list">
                                        @foreach ($motorbikes as $motorbike)
                                            <option value="{{ $motorbike->motorno }}"> {{ $motorbike->motorno }} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">New Motorbike</span>
                                    <input name="currMotorID" class="form-control" list="currMotorID_list" id="currMotorID" value="{{ Request::get('currMotorID') }}" placeholder="Type to search...">
                                    <datalist id="currMotorID_list">
                                        @foreach ($motorbikes as $motorbike)
                                            <option value="{{ $motorbike->motorno }}"> {{ $motorbike->motorno }} </option>
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
                    @if (count($exchanges) > 0)
                    <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    @sortablelink('customer.CustomerName', 'Customer Name')
                                </th>
                                <th>
                                    @sortablelink('preMotoID', 'Last No.')
                                </th>
                                <th>
                                    @sortablelink('currMotorID', 'Current No.')
                                </th>
                                <th>
                                    @sortablelink('created_at', 'Exchange Date')
                                </th>
                                <th>
                                    @sortablelink('staff_id', 'Incharge Staff')
                                </th>
                                <th>
                                    @sortablelink('comment', 'Comment')
                                </th>
                                <th>
                                    @sortablelink('userID', 'Inputer')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exchanges as $exchangeMotor)
                            <tr>
                                <td>
                                    <a href="{{ route('customers.edit',$exchangeMotor->customerID) }}">{{ $exchangeMotor->customer->CustomerName }}</a>
                                </td>
                                @foreach ($motorbikes as $motorbike)
                                    @if ($exchangeMotor->preMotoID == $motorbike->motorID)
                                        <td class="text-center">{{ $motorbike->motorno }}</td>
                                    @endif
                                @endforeach
                                @foreach ($motorbikes as $motorbike)
                                    @if ($exchangeMotor->currMotorID == $motorbike->motorID)
                                        <td class="text-center">{{ $motorbike->motorno }}</td>
                                    @endif
                                @endforeach
                                <td>{{ date('d-M-Y', strtotime($exchangeMotor->created_at)) }}</td>
                                @foreach ($users as $user)
                                    @if ($exchangeMotor->staff_id == $user->id)
                                        <td>{{ $user->name }}</td>
                                    @endif
                                @endforeach
                                <td>
                                    <span>{{ $exchangeMotor->comment }}</span>
                                </td>
                                <td>
                                    <span>{{ $exchangeMotor->user->name }}</span>
                                </td>
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
                                @sortablelink('comment', 'Comment')
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
                            @if ($exchanges->currentPage() > 1)
                                    <li class="page-item first">
                                        <a href="/rentals-report/exchange-motor?page={{ $exchanges->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                    </li>
                            @endif

                                    @for ($i = 1; $i <= $exchanges->lastPage(); $i++)
                                        <li class="page-item {{ $exchanges->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="/rentals-report/exchange-motor?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                @if ($exchanges->currentPage() < $exchanges->lastPage())
                                    <li class="page-item last">
                                        <a href="/rentals-report/exchange-motor?page={{ $exchanges->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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