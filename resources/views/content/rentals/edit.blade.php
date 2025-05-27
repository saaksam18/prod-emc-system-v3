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
        {!! Form::model($rental, ['method' => 'put','route' => ['rentals.update', $rental->rentalID]]) !!}
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
          <div>
            <label class="col-sm-2 col-form-label" for="basic-default-company">Rental Date</label>
            <div class="input-group">
            <span class="input-group-text">Start Date</span>
              <input class="form-control" type="date" id="rental_date" name="rentalDay" onchange="cal()"/>
            <span class="input-group-text">Return Date</span>
              <input class="form-control" type="date" id="return_date" name="returnDate" onchange="cal()"/>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Period</label>
            <div class="input-group">
            <span class="input-group-text">Coming Date</span>
            {!! Form::date('commingDate', null, array('class' => 'form-control', 'id' => 'commingDate')) !!}
            <span class="input-group-text">Rental Period</span>
                {!! Form::text('rentalPeriod', null, array('class' => 'form-control', 'id' => 'peroidOfRent', 'readonly' => 'true')) !!}
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="transactionType">Transaction Type</label>
            <div class="input-group">
              <select name="transactionType" id="transaction_type" class="form-select">
                <option>Status</option>
                <option value="Extension">Extension</option>
                <option value="Return">Return</option>
                <option value="5">Temp. Return</option>
                <option value="3">Sold</option>
                <option value="4">Stolen</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Price</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'placeholder' => 'Amount', 'aria-label' => 'Amount (to the nearest dollar)')) !!}
              <span class="input-group-text">.00</span>
              <select class="form-select" name="staffId" aria-label="staffId">
                <option value="">-- Staff Incharge --</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
            <label for="CustomerName" id="important" class="form-label mt-1" style="display: none">
                <span class="text-danger">
                  <li>
                    If customer early return please use (-) sign before amount
                  </li>
                </span>
            </label>
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
  const rentalPriceInput = document.getElementById('price');
  let returnDateString = '{{ $rental->returnDate }}';
  let returnDateFormat = new Date(returnDateString);
  let returnDate = new Date(returnDateString);
      returnDate.setDate(returnDate.getDate() + 1); // Add 1 to the day
  let newReturnDate = returnDate.toISOString().slice(0, 10);
  let today = new Date().toISOString().slice(0, 10);
  let now = new Date();
  let refundPeroid = parseInt((now - returnDateFormat) / (24 * 3600 * 1000));

  selectElement.addEventListener('change', function() {
    if (this.value === '3' || this.value === '4' || this.value === '5') {
      rentalDateInput.value = today;
      returnDateInput.value = today;
      rentalPeroidInput.value = 0;
      rentalPriceInput.value = ''; 
    } else if (this.value === 'Return'){
      rentalDateInput.value = newReturnDate;
      returnDateInput.value = today;
      rentalPeroidInput.value = refundPeroid;
      rentalPriceInput.value = "";
    } else {
      // Clear the date if not "Return"
      rentalDateInput.value = newReturnDate; 
      returnDateInput.value = ''; 
      rentalPeroidInput.value = 0;
      rentalPriceInput.value = ''; 
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
<script>
  const depositInput = document.getElementById('price');
  const importantLabel = document.getElementById('important');

  depositInput.addEventListener('click', () => {
    importantLabel.style.display = 'block'; // Show the label
  });

  // To hide the label when clicking outside the input (optional)
  document.addEventListener('click', (event) => {
    if (!depositInput.contains(event.target)) {
      importantLabel.style.display = 'none'; // Hide the label
    }
  });
</script>
@endsection
