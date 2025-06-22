@extends('layouts/contentNavbarLayout')

@section('title', 'Exchange Deposit')

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
    <li class="breadcrumb-item active">Exchange Deposit</li>
  </ol>
</nav>
<!--/ Custom style1 Breadcrumb -->

  <!-- Basic Layout -->
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        {{-- Message --}}
        @include('layouts.sections.messages')
        {{-- End Message --}}
      {!! Form::model($rental, ['method' => 'PUT','route' => ['rentals.update-deposit', $rental->rentalID]]) !!}
      @csrf
      <div class="row mb-3">
        <label class="form-label" for="basic-default-name">Customer Name <span class="text-danger">*</span></label>
        <div class="input-group">
          <select id="customer" name="customer" class="form-control" data-live-search="true" disabled> 
            <option selected>-- Select Customer --</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->customerID }}" {{ $customer->customerID == $rental->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
            @endforeach
          </select>
          <input type="hidden" class="form-control" name="customerID" id="customerID" value="{{ $customer->customerID }}" />
        </div>
      </div>
      <div class="row">
        <label class="form-label">Previous Deposit <span class="text-danger">*</span></label>
      </div>
      @foreach ($pre_deposits as $pre_deposit)
      <div class="row mb-3">
        <div class="input-group">
          <select name="preDepositType" class="form-select" disabled>
              <option value="{{ $pre_deposit->currDepositType }}" {{ $pre_deposit->customerID == $rental->customerID ? 'selected' : '' }}>{{ $pre_deposit->currDepositType }}</option>
          </select>
          <input readonly type="text" class="form-control" value="{{ $pre_deposit->currDeposit }}">
        </div>
      </div>
      @endforeach
      <div class="row">
        <label class="form-label">New Deposit<span class="text-danger">* Note: Don't put ($) Sign</span></label>
      </div>
      <div class="row mb-3">
        <div class="input-group" id="newDepositDiv">
          <select name="inputs[0][currDepositType]" class="form-select">
            <option value="N/A">-- Deposit Type --</option>
            <option value="Passport">Passport</option>
            <option value="Money">Money</option>
            <option value="Others">Others</option>
          </select>
          <input type="text" name="inputs[0][currDeposit]" placeholder="New Deposit" class="form-control">
          <button type="button" id="add-button" name="add-button" class="btn btn-warning">Add More</button>
        </div>
      </div>
      <div class="row mb-3">
        <label name="comment" for="comment" class="form-label">Remark/Comment <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="textarea" name="comment" class="form-control" id="comment"/>
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
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
      {!! Form::close() !!}
      </div>
  </div>
</div>
</div>

<script>
  var i = 0;
  $('#add-button').click(function () {
    ++i;
    $('#newDepositDiv').append (
      `
        <div class="input-group mt-3" id="newDepositDivPlus">
          <select name="inputs[`+i+`][currDepositType]" class="form-select">
            <option value="N/A">-- Deposit Type --</option>
            <option value="Passport">Passport</option>
            <option value="Money">Money</option>
            <option value="Others">Others</option>
          </select>
          <input type="text" name="inputs[`+i+`][currDeposit]" placeholder="New Deposit" class="form-control" />
          <button type="button" id="rm-button" name="rm-button" class="btn btn-danger">Remove</button>
        </div>
      `);
  });

  $(document).on('click', '#rm-button', function() {
    $(this).parents('#newDepositDivPlus').remove();
  });
</script>

@endsection
