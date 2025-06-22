@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')

<div class="row">
  <div class="col-lg-8 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Welcome to the System! Dashboard 2.0🎉</h5>
            <p class="mb-4">Waiting for you instruction. <span class="fw-bold">New Customer?</span> Click the button</p>

            <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-outline-primary">Manage Rental</a>
            <a href="{{ route('print-stock') }}" class="btn btn-sm btn-outline-warning">Print Stock</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 order-1">
    <div class="row mb-4">
      <div class="col-lg-6 col-md-12 col-6">
        <div class="card">
          <div class="card-body">
            <span class="fw-semibold d-block mb-2">Visa Reminder</span>
            <h4 class="card-title">{{$totalCustomers}}</h4>
            <h6 class="mb-3">Customers</h6>
            <a class="btn btn-sm btn-outline-danger" href="{{ route('visa.reminder') }}">View More</a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6">
        <div class="card">
          <div class="card-body">
            <span class="fw-semibold d-block mb-2">WP Remindrer</span>
            <h4 class="card-title">{{$totalWPCustomers}}</h4>
            <h6 class="mb-3">Customers</h6>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('work-permit.reminder') }}">View More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Monthly Rental -->
  <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-md-12">
          <h5 class="card-header m-0 me-2 pb-3">Yearly Rental</h5>
          <div id="YearlyChart"></div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Monthly Rental -->
  <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
    <div class="row">
      <div class="col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="Credit Card" class="rounded">
              </div>
            </div>
            <span class="d-block mb-1">Total Cash Deposit</span>
            <h3 class="card-title text-nowrap mb-2">${{ $countCashs }}</h3>
            {{-- <small class="text-danger fw-semibold"><i class='bx bx-down-arrow-alt'></i> -14.82%</small> --}}
          </div>
        </div>
      </div>
      <div class="col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/cc-primary.png')}}" alt="Credit Card" class="rounded">
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Total Passport Deposit</span>
            <h3 class="card-title mb-2">{{ $countPPs }}</h3>
            {{-- <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> +28.14%</small> --}}
          </div>
        </div>
      </div>

      {{-- Weekly Rental --}}
      <div class="col-12 mb-3">
        <div class="card">
          <div class="card-body">
                <div class="card-title">
                <h5 class="text-nowrap mt-1">Motorbikes</h5>
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
    </div>
  </div>
</div>
{{-- End Weekly Rental --}}

  <!-- Customer Late Payment -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <h5 class="card-header">Customer Late Payment</h5>
      <div class="ms-3 me-3">
          {{-- Message --}}
          @include('layouts.sections.messages')
          {{-- End Message --}}
      </div>
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
                        @sortablelink('motorInfor.motorno', 'Motor No.')
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
                        <a href="{{ route('rentals.add-coming-date',$rental->rentalID) }}">{{ $rental->customer->CustomerName }}</a>
                      </td>
                      <td class="text-center">
                          <a href="{{ route('rentals.changeMotorEdit',$rental->rentalID) }}">{{ $rental->motorInfor->motorno }}</a>
                      </td>
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
                                  <a href="{{ route('contacts.edit', $customer_contact->customerID) }}">
                                      <li>
                                          {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                      </li>
                                  </a>
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
                      @sortablelink('motorInfor.motorno', 'Motor No.')
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
                              <a href="/home?page={{ $rentals->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                          </li>
                  @endif
                          @for ($i = 1; $i <= $rentals->lastPage(); $i++)
                              <li class="page-item {{ $rentals->currentPage() == $i ? 'active' : '' }}">
                                  <a class="page-link" href="/home?page={{ $i }}">{{ $i }}</a>
                              </li>
                          @endfor

                      @if ($rentals->currentPage() < $rentals->lastPage())
                          <li class="page-item last">
                              <a href="/home?page={{ $rentals->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                          </li>
                      @endif
              </ul>
          </nav>
      </div>
    </div>
  </div>
</div>
<!--/ Contextual Classes -->

<script>
  var options = {
          series: [{
          data: {!! json_encode($yearData['data']) !!}
        }],
          chart: {
          type: 'bar',
          height: 300
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        colors: [config.colors.primary, config.colors.info],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: {!! json_encode($yearData['categories']) !!},
        },
        yaxis: {
          title: {
            text: 'New Rental Motorbikes'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " motorbikes"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#YearlyChart"), options);
        chart.render();
</script>

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
