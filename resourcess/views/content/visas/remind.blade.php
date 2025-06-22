@extends('layouts/contentNavbarLayout')

@section('title', 'Visa Customer Remind')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Visa Customers Reminder</li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="bg-primary text-white card-header">Visa Customers</h5>
            <div class="table-responsive text-nowrap">
                <div class="ms-3 me-3">
                    {{-- Message --}}
                    <div class="mt-3">
                        @include('layouts.sections.messages')
                    </div>
                    {{-- End Message --}}
                    <label class="col-form-label">Table Data</label>
                </div>
                @if (count($visas) > 0)
                <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-primary">Actions</th>
                            <th>
                                @sortablelink('visaID', 'No.')
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
                                @sortablelink('visaType', 'Visa Type')
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
                        @foreach ($visas as $visa)
                            <tr class="table-danger">
                                <td>
                                    <form style="display:inline" method="POST" action="{{ route('visas.reminded', $visa->visaID) }}" onsubmit="return confirm('Are you sure customer {{ $visa->customer->CustomerName }} is reminded ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            Remind Now
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $i++ }}</td>
                                <td>
                                    <a href="{{ route('customers.edit',$visa->customerID) }}">{{ $visa->customer->CustomerName }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $visa->customer->gender }}
                                </td>
                                <td class="text-center">
                                    {{ $visa->customer->nationality }}
                                </td>
                                <td>
                                    @foreach ($customer_contacts as $customer_contact)
                                        @if ($customer_contact->customerID == $visa->customerID)
                                            <li>
                                                {{ $customer_contact->contactType }} : {{ $customer_contact->contactDetail }}
                                            </li>
                                        @endif
                                    @endforeach
                                </td>
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
            <!--/ Basic Pagination -->
                @else
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-primary">Actions</th>
                            <th>
                                @sortablelink('visaID', 'No.')
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
                                @sortablelink('visaType', 'Visa Type')
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
                <p class="text-center">No visa customer found.</p>
            @endif
            </div>

            <!-- Basic Pagination -->
            <div class="demo-inline-spacing">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @if ($visas->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="/visas/reminder?page={{ $visas->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                        @endif

                                @for ($i = 1; $i <= $visas->lastPage(); $i++)
                                    <li class="page-item {{ $visas->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="/visas/reminder?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($visas->currentPage() < $visas->lastPage())
                                <li class="page-item last">
                                    <a href="/visas/reminder?page={{ $visas->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
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