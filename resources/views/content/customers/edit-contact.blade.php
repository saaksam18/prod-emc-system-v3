@extends('layouts/contentNavbarLayout')

@section('title', 'Change Customer Contact')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap-select-country.js')}}"></script>
@endsection

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('customers.index') }}">Customer Information</a>
      </li>
      <li class="breadcrumb-item active">Change Customer Contact</li>
    </ol>
</nav>
      <!--/ Custom style1 Breadcrumb -->

<div class="col-xxl">
    <div class="card mb-4">
        <div class="card-body">
          {{-- Message --}}
          @include('layouts.sections.messages')
          {{-- End Message --}}
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model($contacts, ['method' => 'PUT','route' => ['contacts.update', $contacts->customerID]]) !!}
            @csrf
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name*</label>
              <div class="input-group">
                <select id="customer" name="customer" class="form-control" data-live-search="true" disabled> 
                  <option selected>-- Select Customer --</option>
                  @foreach ($customers as $customer)
                      <option value="{{ $customer->customerID }}" {{ $customer->customerID == $contacts->customerID ? 'selected' : '' }}>{{ $customer->CustomerName }}</option>
                  @endforeach
                </select>
                <input type="hidden" class="form-control" name="CustomerName" id="CustomerName" value="{{ $customer->customerID }}" />
              </div>
            </div>
            <div class="row mb-3">
              <label class="form-label">Previous Customer Contact <strong class="text-danger">*</strong></label>
              @foreach ($old_contacts as $old_contact)
              <div class="input-group">
                <select name="old_contactType_dis" class="form-select mb-3" disabled>
                        <option value="{{ $old_contact->contactType }}">{{ $old_contact->contactType }}</option>
                </select>
                <input hidden type="text" name="old_contactType" class="form-control" id="old_contactType" value="{{ $old_contact->contactType }}" placeholder="Contact Details" aria-describedby="contactDetail" />
                <input type="text" name="old_contactDetail_dis" class="form-control mb-3" id="old_contactDetail_dis" value="{{ $old_contact->contactDetail }}" placeholder="Contact Details" aria-describedby="contactDetail" readonly/>
                <input hidden type="text" name="old_contactDetail" class="form-control" id="old_contactDetail" value="{{ $old_contact->contactDetail }}" placeholder="Contact Details" aria-describedby="contactDetail" />
              </div>
              @endforeach
            </div>
            <div class="row mb-3" id="newContactDiv">
              <label class="form-label">New Customer Contact <strong class="text-danger">*</strong></label>
              <div class="input-group">
                <select name="inputs[0][contactType]" class="form-select">
                  <option value="N/A">-- Contact Type --</option>
                    <option value="Mobile Phone">Mobile Phone</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Telegram">Telegram</option>
                    <option value="WhatsApp">WhatsApp</option>
                    <option value="Others">Others</option>
                  </select>
                <input type="text" name="inputs[0][contactDetail]" class="form-control" id="contactDetail" placeholder="Contact Details" aria-describedby="contactDetail" />
                <button type="button" id="add-button" name="add-button" class="btn btn-warning">Add More</button>
              </div>
            </div>
                  <div class="row">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Save</button>
                      <button type="button" class="btn btn-dark">
                        <a href="{{ route('customers.index') }}" style="color: white">View All Customer</a>
                      </button>
                    </div>
                  </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    var i = 0;
    $('#add-button').click(function () {
      ++i;
      $('#newContactDiv').append (
        `
          <div class="input-group mt-3" id="newContactDivPlus">
            <select name="inputs[`+i+`][contactType]" class="form-select">
              <option value="N/A">-- Contact Type --</option>
                <option value="Mobile Phone">Mobile Phone</option>
                <option value="Facebook">Facebook</option>
                <option value="Telegram">Telegram</option>
                <option value="WhatsApp">WhatsApp</option>
                <option value="Others">Others</option>
            </select>
            <input type="text" name="inputs[`+i+`][contactDetail]" placeholder="Contact Details" class="form-control" />
            <button type="button" id="rm-button" name="rm-button" class="btn btn-danger">Remove</button>
          </div>
        `);
    });
  
    $(document).on('click', '#rm-button', function() {
      $(this).parents('#newContactDivPlus').remove();
    });
  </script>

@endsection