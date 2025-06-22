@extends('layouts/contentNavbarLayout')

@section('title', 'Customer Overdue')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        <li class="breadcrumb-item active">Overdue Customer</li>
    </ol>
  </nav>

  <!-- Customer Late Payment -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <h5 class="card-header bg-primary text-white">Customer Late Payment</h5>
        <div class="table-responsive text-nowrap">
          <table class="table table-hover table-bordered">
            @if (count($rentals) > 0)
            <table class="table table-hover table-bordered text-nowrap">
              <thead>
                  <tr>
                      <th>
                          @sortablelink('customer.CustomerName', 'Customer Name')
                      </th>
                      <th>
                          @sortablelink('motorID', 'Motor No.')
                      </th>
                      <th>
                          @sortablelink('customer.gender', 'Gender')
                      </th>
                      <th>
                          @sortablelink('Deposit')
                      </th>
                      <th>
                          @sortablelink('Contact')
                      </th>
                      <th>
                          @sortablelink('returnDate', 'Return Date')
                      </th>
                      <th>
                          @sortablelink('commingDate', 'Comming Date')
                      </th>
                      <th>
                          @sortablelink('remainingDays', 'Late Day')
                      </th>
                      <th>
                          @sortablelink('staff_id', 'Incharger')
                      </th>
                  </tr>
              </thead>
            <tbody class="table-border-bottom-0">
              @foreach ($rentals as $rental)
                    <tr class="table-danger">
                        <td>
                          <a href="{{ route('rentals.edit',$rental->rentalID) }}">{{ $rental->customer->CustomerName }}</a>
                        </td>
                        <td class="text-center">{{ $rental->motorInfor->motorno }}</td>
                        <td>{{ $rental->customer->gender }}</td>
                        <td>
                            <a href="{{ route('rentals.exchange-deposit', $rental->rentalID) }}">
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
                                                <li>Add Deposit</li>
                                        @endif
                                    @endif
                                @endforeach
                            </a>
                        </td>
                        <td>
                            @foreach ($customer_contacts as $customer_contact)
                                @if ($customer_contact->customerID == $rental->customerID)
                                    <li>
                                        {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                    </li>
                                @endif
                            @endforeach
                        </td>
                          <td>{{ date('d-M-Y', strtotime($rental->returnDate)) }}</td>
                        @if ($rental->commingDate != null)
                          <td>{{ date('d-M-Y', strtotime($rental->commingDate)) }}</td>
                        @else
                          <td>No Data</td>
                        @endif
                        <td>- {{ $rental->remainingDays }} Days</td>
                        @foreach ($users as $user)
                            @if ($rental->staff_id == $user->id)
                                <td>{{ $user->name }}</td>
                            @endif
                        @endforeach
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
                        @sortablelink('customer.CustomerName', 'Customer Name')
                    </th>
                    <th>
                        @sortablelink('motorID', 'Motor No.')
                    </th>
                    <th>
                        @sortablelink('customer.gender', 'Gender')
                    </th>
                    <th>
                        @sortablelink('Deposit')
                    </th>
                    <th>
                        @sortablelink('Contact')
                    </th>
                    <th>
                        @sortablelink('returnDate', 'Return Date')
                    </th>
                    <th>
                        @sortablelink('commingDate', 'Comming Date')
                    </th>
                    <th>
                        @sortablelink('remainingDays', 'Late Day')
                    </th>
                    <th>
                        @sortablelink('staff_id', 'Incharger')
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
                                <a href="/rentals-report/overdue-customer?page={{ $rentals->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                            </li>
                    @endif
                            @for ($i = 1; $i <= $rentals->lastPage(); $i++)
                                <li class="page-item {{ $rentals->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="/rentals-report/overdue-customer?page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor
  
                        @if ($rentals->currentPage() < $rentals->lastPage())
                            <li class="page-item last">
                                <a href="/rentals-report/overdue-customer?page={{ $rentals->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                            </li>
                        @endif
                </ul>
            </nav>
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