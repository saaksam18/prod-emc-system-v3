@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Customer Information')

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
      <li class="breadcrumb-item active">Edit Customer Information</li>
    </ol>
</nav>
      <!--/ Custom style1 Breadcrumb -->

<div class="col-xxl">
    <div class="card mb-4">
        <div class="card-body">
          {{-- Message --}}
          @include('layouts.sections.messages')
          {{-- End Message --}}

            {!! Form::model($customer, ['method' => 'PUT','route' => ['customers.update', $customer->customerID]]) !!}
            @csrf
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name*</label>
                    <div class="input-group">
                        {!! Form::text('CustomerName', null, array('class' => 'form-control')) !!}
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Customer Detail*</label>
                    <div class="input-group">
                      {!! Form::text('nationality', null, array('class' => 'form-control', 'list' => 'nationality', 'placeholder' => '-- Nationality --' )) !!}
                      <datalist id="nationality">
                        @foreach ($countriesList as $country)
                          <option value="{{ $country->nationality }}"> {{ $country->nationality }} </option>
                        @endforeach
                      </datalist> 
                      <select id="gender" name="gender" class="form-select">
                        <option value="N/A" {{ $customer->gender == 'N/A' ? 'selected' : '' }}>N/A</option>
                        <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="In-Between"> {{ $customer->gender == 'In-Between' ? 'selected' : '' }}In-Between</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                    <div class="input-group">
                      {!! Form::textarea('address', null, array('class' => 'form-control', 'rows' => 3)) !!}
                    </div>
                    <label for="exampleFormControlTextarea1" class="form-label">Remark/Comment</label>
                    <div class="input-group">
                      {!! Form::textarea('comment', null, array('class' => 'form-control', 'rows' => 1)) !!}
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

@endsection