@extends('layouts/contentNavbarLayout')

@section('title', 'WP Customer Remind')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">WP Customers Reminder</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="bg-primary text-white card-header">Work Permit Customers Reminder</h5>
            <div class="table-responsive text-nowrap">
                <div class="ms-3 me-3">
                    {{-- Message --}}
                    <div class="mt-3">
                        @include('layouts.sections.messages')
                    </div>
                    {{-- End Message --}}
                    <label class="col-form-label">Table Data</label>
                </div>
                @if (count($wps) > 0)
                <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-primary">Actions</th>
                            <th>
                                @sortablelink('wpID', 'No.')
                            </th>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('customer.gender', 'Gender')
                            </th>
                            <th>
                                @sortablelink('customer.nationality', 'Nationality')
                            </th>
                            <th>
                                @sortablelink('Contact')
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wps as $wp)
                            <tr class="table-danger">
                                <td class="text-center">
                                    <form style="display:inline" method="POST" action="{{ route('work-permit.reminded', $wp->wpID) }}" onsubmit="return confirm('Are you sure customer {{ $wp->customer->CustomerName }} is reminded ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            Remind Now
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $i++ }}</td>
                                <td>
                                    <a href="{{ route('customers.edit',$wp->customerID) }}">{{ $wp->customer->CustomerName }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $wp->customer->gender }}
                                </td>
                                <td class="text-center">
                                    {{ $wp->customer->nationality }}
                                </td>
                                <td>
                                    @foreach ($customer_contacts as $customer_contact)
                                        @if ($customer_contact->customerID == $wp->customerID)
                                            <li>
                                                {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                            </li>
                                        @endif
                                    @endforeach
                                </td>
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
            <!--/ Basic Pagination -->
                @else
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-primary">Actions</th>
                            <th>
                                @sortablelink('wpID', 'No.')
                            </th>
                            <th>
                                @sortablelink('customer.CustomerName', 'Customer Name')
                            </th>
                            <th>
                                @sortablelink('customer.gender', 'Gender')
                            </th>
                            <th>
                                @sortablelink('customer.nationality', 'Nationality')
                            </th>
                            <th>
                                @sortablelink('Contact')
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
                        </tr>
                    </thead>
                </table><br/>
                <p class="text-center">No work permit customer found.</p>
            @endif
            </div>

            <!-- Basic Pagination -->
            <div class="demo-inline-spacing">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @if ($wps->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="/work-permit/reminder?page={{ $wps->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                        @endif

                                @for ($i = 1; $i <= $wps->lastPage(); $i++)
                                    <li class="page-item {{ $wps->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="/work-permit/reminder?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($wps->currentPage() < $wps->lastPage())
                                <li class="page-item last">
                                    <a href="/work-permit/reminder?page={{ $wps->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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