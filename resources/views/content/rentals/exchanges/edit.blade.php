@extends('layouts/contentNavbarLayout')

@section('title', 'Exchange Motorbike')

@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('rentals.index') }}">Rental Management</a>
    </li>
    <li class="breadcrumb-item active">Exchange Motorbike</li>
  </ol>
</nav>
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-body">
        {{-- Message --}}
        @include('layouts.sections.messages')
        {{-- End Message --}}
        {!! Form::model($rental, ['method' => 'put','route' => ['rentals.exchange-motor', $rental->rentalID]]) !!}
        @csrf
        {!! Form::hidden('rentalID', null, array('class' => 'form-control')) !!}
        <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name</label>
        <div class="input-group mb-3">
          <select id="customer" name="customerID" class="form-control" data-live-search="true" disabled> 
            <option selected>-- Select Customer --</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->customerID }}" {{ $customer->customerID == $rental->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
            @endforeach
          </select>
          <input type="hidden" class="form-control" name="customerID" id="customerID" value="{{ $customer->customerID }}" />
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="basic-default-company">Previous Motorbike</label>
          <div class="input-group">
            <span class="input-group-text">Motorbike No.</span>
            <select id="motorID" name="motorID" class="form-control" disabled> 
              <option selected>-- Select Motorbike --</option>
              @foreach ($motorbikes as $motorbike)
                  <option value="{{ $motorbike->motorID }}" {{ $motorbike->motorID == $rental->motorID ? 'selected' : '' }}>{{ $motorbike->motorno }}</option>
              @endforeach
            </select>
            <input type="hidden" class="form-control" name="oldMotorbikeID" id="oldMotorbikeID" value="{{ $rental->motorID }}" />
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="basic-default-company">New Motorbike</label>
          <div class="input-group">
            <span class="input-group-text">Motorbike No.</span>
            <select id="motorID" name="motorID" class="form-control select2">
                <option selected>-- Select Motrobike --</option>
                @foreach ($motorbikes as $motorbike)
                  @if ($motorbike->motorStatus == '1')
                    <option value="{{ $motorbike->motorID }}">{{ $motorbike->motorno }}</option>
                  @endif
                @endforeach
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Remark/Comment</label>
          <div class="input-group">
            {!! Form::textarea('comment', null, array('class' => 'form-control', 'rows' => 3)) !!}
          </div>
        </div>
        <div class="row mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Staff Incharge</label>
          <div class="input-group">
            <select class="form-select" name="staffId" aria-label="staffId">
              <option value="">-- Staff Incharge --</option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $user->id == $rental->staff_id ? 'selected' : '' }}>{{ $user->name }}</option>
              @endforeach
            </select>
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
