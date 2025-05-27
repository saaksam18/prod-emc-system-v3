@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Visa Customer')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
          <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('visas.index') }}">All Visa Customer</a>
        </li>
        <li class="breadcrumb-item active">Edit Visa Customer</li>
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
        {!! Form::model($visas, ['method' => 'PUT','route' => ['visas.update', $visas->visaID]]) !!}
          {!! csrf_field() !!}
          <div class="row mb-3">
            {!! Form::hidden('visaID', null, array('class' => 'form-control')) !!}
            <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name</label>
            <div class="input-group">
              <select id="customerID" name="customerID" class="form-control" data-live-search="true" disabled> 
                <option selected>-- Select Customer --</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->customerID }}" {{ $customer->customerID == $visas->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">General Information</label>
            <div class="input-group">
              <span class="input-group-text">Passport Amount</span>
                {!! Form::text('amount', null, array('class' => 'form-control')) !!}
                <span class="input-group-text">Visa Type</span>
                <select class="form-select" name="visaType" id="exampleFormControlSelect1" aria-label="Default select example">
                    <option value="EB" {{ $visas->visaType == 'EB' ? 'selected' : '' }}>EB</option>
                    <option value="ER" {{ $visas->visaType == 'ER' ? 'selected' : '' }}>ER</option>
                    <option value="EP" {{ $visas->visaType == 'EP' ? 'selected' : '' }}>EP</option>
                    <option value="ES" {{ $visas->visaType == 'ES' ? 'selected' : '' }}>ES</option>
                </select>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Expiration Date</label>
            <div class="input-group">
            <span class="input-group-text">Expiration Date</span>
                {!! Form::date('expireDate', null, array('class' => 'form-control', 'id' => 'expireDate')) !!}
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Incharge Staff</label>
            <div class="input-group">
              <select class="form-select" name="staffID" aria-label="staffID">
                <option value="">-- Staff Incharge --</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ $visas->staff_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-warning">Save</button>
              <button type="button" class="btn btn-primary">
                <a href="{{ route('visas.index') }}" style="color: white">All Visa Customer</a>
              </button>
            </div>
          </div>
          {!! Form::close() !!}
      </div>
    </div>
  </div>

<script>
  const expireDateField = document.getElementById('expireDate');
  let expireDateString = '{{ $visas->expireDate }}';
  let expireDateFormat = new Date(expireDateString);
  let expireDate = new Date(expireDateString);
      //expireDate.setDate(expireDate.getDate() + 1); // Add 1 to the day
  let newExpireDate = expireDate.toISOString().slice(0, 10);
  console.log(newExpireDate)

  if (expireDateString) {
    expireDateField.value = newExpireDate;
  } else {
    expireDateField.value = ""; 
  }
</script>
@endsection
