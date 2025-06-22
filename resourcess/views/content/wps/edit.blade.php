@extends('layouts/contentNavbarLayout')

@section('title', 'Edit WP Customer')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
          <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Edit Work Permit Customer</li>
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
        {!! Form::model($wps, ['method' => 'PUT','route' => ['work-permit.update', $wps->wpID]]) !!}
          {!! csrf_field() !!}
          <div class="row mb-3">
            {!! Form::hidden('wpID', null, array('class' => 'form-control')) !!}
            <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name</label>
            <div class="input-group">
              <select id="customerID" name="customerID" class="form-control" data-live-search="true" disabled> 
                <option selected>-- Select Customer --</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->customerID }}" {{ $customer->customerID == $wps->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Expiration Date</label>
            <div class="input-group">
            <span class="input-group-text">Expiration Date</span>
            <input class="form-control" name="wpExpireDate" id="exp_date" type="date" value="<?php echo date('Y-12-31'); ?>"/>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Incharge Staff</label>
            <div class="input-group">
              <select class="form-select" name="staffID" aria-label="staffID">
                <option value="">-- Staff Incharge --</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ $user->id == $wps->staff_id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-warning">Save</button>
              <button type="button" class="btn btn-primary">
                <a href="{{ route('work-permit.index') }}" style="color: white">All Work Permit Customer</a>
              </button>
            </div>
          </div>
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  const expireDate = document.getElementById("exp_date");
  const remindDate = document.getElementById("reminderDate");
  console.log(expireDate);

  expireDate.addEventListener("change", () => {
    const date = new Date(expireDate.value);
    remindDate.value = date.toISOString().slice(0, 10) - 1;
  });
</script>
@endsection
