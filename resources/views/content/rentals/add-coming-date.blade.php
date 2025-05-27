@extends('layouts/contentNavbarLayout')

@section('title', 'Rental Extension')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('rentals.index') }}">Rental Management</a>
    </li>
    <li class="breadcrumb-item active">Rental Update</li>
  </ol>
</nav>
<!--/ Custom style1 Breadcrumb -->
    <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-body">
        {{-- Message --}}
        @include('layouts.sections.messages')
        {{-- End Message --}}
        {!! Form::model($rental, ['method' => 'put','route' => ['rentals.update-coming-date', $rental->rentalID]]) !!}
        @csrf
        <div class="row mb-3">
            {!! Form::hidden('rentalID', null, array('class' => 'form-control')) !!}
            <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name</label>
            <div class="input-group">
              <select id="customerID" name="customerID" class="form-control" data-live-search="true" disabled> 
                <option selected>-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->customerID }}" {{ $customer->customerID == $rental->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
                @endforeach
              </select>
              {{-- To Send CustomerID --}}
              <select id="customerID" name="customerID" class="form-control" data-live-search="true" hidden> 
                <option selected>-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->customerID }}" {{ $customer->customerID == $rental->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
                @endforeach
              </select>
              {{-- End To Send CustomerID --}}
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Motorbike</label>
            <div class="input-group">
              <span class="input-group-text">Motorbike No.</span>
              <select id="motorID" name="motorID" class="form-control" disabled> 
                <option selected>-- Select Motorbike --</option>
                @foreach ($motorbikes as $motorbike)
                    <option value="{{ $motorbike->motorID }}" {{ $motorbike->motorID == $rental->motorID ? 'selected' : '' }}>{{ $motorbike->motorno }}</option>
                @endforeach
              </select>
              {{-- To Send motorID --}}
              <select id="motorID" name="motorID" class="form-control" hidden> 
                <option selected>-- Select Motorbike --</option>
                @foreach ($motorbikes as $motorbike)
                    <option value="{{ $motorbike->motorID }}" {{ $motorbike->motorID == $rental->motorID ? 'selected' : '' }}>{{ $motorbike->motorno }}</option>
                @endforeach
              </select>
              {{-- End To Send motorID --}}
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Coming Date</label>
            <div class="input-group">
            <span class="input-group-text">Coming Date</span>
            {!! Form::date('commingDate', null, array('class' => 'form-control', 'id' => 'commingDate')) !!}
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-warning">Save</button>
            </div>
          </div>
          {!! Form::close() !!}
      </div>
  </div>
</div>
</div>

<script>
  const selectElement = document.getElementById('transaction_type');
  const rentalDateInput = document.getElementById('rental_date');
  const returnDateInput = document.getElementById('return_date');
  const rentalPeroidInput = document.getElementById('peroidOfRent');

  selectElement.addEventListener('change', function() {
    if (this.value === 'Return' || this.value === '3' || this.value === '4' || this.value === '5') {
      const today = new Date().toISOString().slice(0, 10);
      rentalDateInput.value = today;
      returnDateInput.value = today;
      rentalPeroidInput.value = 0;
    } else {
      rentalDateInput.value = ''; // Clear the date if not "Return"
      returnDateInput.value = ''; // Clear the date if not "Return"
      rentalPeroidInput.value = ''; // Clear the date if not "Return"
    }
  });
</script>

<script type="text/javascript">
  function GetDays(){
          var returnDate = new Date(document.getElementById("return_date").value);
          var rentalDate = new Date(document.getElementById("rental_date").value);
          return parseInt((returnDate - rentalDate) / (24 * 3600 * 1000));
  }

  function cal(){
  if(document.getElementById("return_date")){
      document.getElementById("peroidOfRent").value=GetDays();
  }  
}

</script>
@endsection
